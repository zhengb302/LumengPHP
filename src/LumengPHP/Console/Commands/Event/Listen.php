<?php

namespace LumengPHP\Console\Commands\Event;

use LumengPHP\Components\Queue\QueueInterface;
use LumengPHP\Console\InputInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Event\EventManagerInterface;
use ReflectionClass;

/**
 * 事件监听命令
 * 
 * 要求安装并启用了<b>PCNTL</b>和<b>POSIX</b>扩展
 * 
 * 根据事件配置，会为每一个事件队列开启一个工作进程(worker)，一个队列对应一个工作进程，多个事件可以共享一个队列。
 * 工作进程会监听队列里的事件数据，一旦有事件到达，便执行此事件的监听器，在执行完此事件所有的监听器之后，
 * 工作进程会继续监听事件数据，如果没有新的事件到达，要么阻塞(使用了阻塞型的队列)，要么退出(使用了非阻塞型的队列或阻塞超时)。
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Listen {

    /**
     * @var AppContextInterface
     * @service
     */
    private $appContext;

    /**
     * @var InputInterface 
     * @service
     */
    private $input;

    /**
     * @var string pid文件路径
     */
    private $pidFile;

    /**
     * @var array 工作进程Map，格式：工作进程ID => 事件队列服务对象名称
     */
    private $workerMap = [];

    /**
     * @var bool 当前进程是否为主进程
     */
    private $isMaster = true;

    /**
     * @var bool 指示是否收到SIGTERM信号要退出执行
     */
    private $shouldExit = false;

    /**
     * @var EventManagerInterface 
     * @service
     */
    private $eventManager;

    public function init() {
        $projectName = basename($this->appContext->getRootDir());
        $this->pidFile = "/var/run/{$projectName}.event-listend.pid";
    }

    public function execute() {
        $action = $this->input->getArg(1);
        if (!$action) {
            $action = 'start';
        }

        switch ($action) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'restart':
                $this->restart();
                break;
            default:
                _throw("未知的操作：{$action}");
        }
    }

    /**
     * 启动
     * 
     * 示例：
     *     ./console event:listen
     *     ./console event:listen start
     */
    private function start() {
        if (file_exists($this->pidFile)) {
            _throw('事件监听守护进程已经在运行中');
        }

        $queueServices = $this->extraQueueServices();

        //如果没有(需要)队列化的异步事件
        if (empty($queueServices)) {
            _throw('没有需要队列化的异步事件');
        }

        //当前进程转为守护进程
        $this->daemon();

        //为每个事件队列分别开启一个工作进程
        foreach ($queueServices as $queueServiceName) {
            $this->startWorker($queueServiceName);
        }

        //主进程做一些管理工作
        while (true) {
            //如果收到SIGTERM信号要求退出，且工作进程已经全部退出，则删除PID文件，然后退出
            if ($this->shouldExit && empty($this->workerMap)) {
                unlink($this->pidFile);
                exit(0);
            }

            pcntl_signal_dispatch();
            $this->waitWorker();
            sleep(3);
        }
    }

    /**
     * 提取出所有的事件队列名称
     * 
     * @return array
     */
    private function extraQueueServices() {
        $eventConfig = $this->appContext->getAppSetting()->getEventConfig();
        if (empty($eventConfig)) {
            return [];
        }

        $queueServices = [];
        foreach (array_keys($eventConfig) as $eventName) {
            $refObj = new ReflectionClass($eventName);
            $metadataLoader = new ClassMetadataLoader($this->appContext, $refObj);
            $classMetadata = $metadataLoader->load();

            //如果不是队列化的异步事件
            if (!isset($classMetadata['queued'])) {
                continue;
            }

            $queueServiceName = $classMetadata['queued'] ?: 'defaultEventQueue';
            if (!in_array($queueServiceName, $queueServices)) {
                $queueServices[] = $queueServiceName;
            }
        }

        return $queueServices;
    }

    /**
     * 转为守护进程
     */
    private function daemon() {
        umask(0);

        $pid = pcntl_fork();

        //创建子进程出错
        if ($pid < 0) {
            _throw('创建子进程出错');
        }
        //父进程，退出
        elseif ($pid) {
            exit(0);
        }

        //使当前进程成为会话领导进程(从控制终端脱离)
        if (posix_setsid() == -1) {
            _throw('设置当前进程为会话领导进程出错');
        }

        //切换工作目录到根目录
        if (!chdir('/')) {
            _throw('切换工作目录到根目录出错');
        }

        //关闭标准输入、输出、错误，并重定向到/dev/null
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        fopen('/dev/null', 'r');
        fopen('/dev/null', 'a');
        fopen('/dev/null', 'a');

        //设置信号处理器
        $sigHandler = [$this, 'sigTermHandler'];
        pcntl_signal(SIGTERM, $sigHandler);

        //已成功转为守护进程，把主进程ID写入pid文件中
        file_put_contents($this->pidFile, getmypid());
    }

    /**
     * SIGTERM信号处理器
     * 
     * @param int $signo
     */
    private function sigTermHandler($signo) {
        //主进程收到SIGTERM信号，设置标志，挨个向工作进程发送SIGTERM信号
        if ($this->isMaster) {
            $this->shouldExit = true;
            foreach (array_keys($this->workerMap) as $workPid) {
                posix_kill($workPid, SIGTERM);
            }
        }
        //工作进程收到SIGTERM信号，退出执行
        else {
            exit(0);
        }
    }

    /**
     * 启动工作进程
     * 
     * @param string $queueServiceName
     */
    private function startWorker($queueServiceName) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            _throw('创建工作进程失败');
        }
        //父进程
        else if ($pid) {
            $this->workerMap[$pid] = $queueServiceName;
        }
        //子进程
        else {
            $this->isMaster = false;

            $this->listenQueue($queueServiceName);

            //必须退出啊，不然就跑去执行主进程的代码了
            exit(0);
        }
    }

    /**
     * 监听队列
     * 
     * @param string $queueServiceName 队列服务名称
     */
    private function listenQueue($queueServiceName) {
        /* @var $queueService QueueInterface */
        $queueService = $this->appContext->getService($queueServiceName);
        while ($event = $queueService->dequeue()) {
            $this->eventManager->trigger($event, true);

            pcntl_signal_dispatch();
        }
    }

    /**
     * 等待工作进程
     */
    private function waitWorker() {
        if (empty($this->workerMap)) {
            return;
        }

        $status = 0;
        if ($this->shouldExit) {
            //收到SIGTERM信号要求退出，挨个等待工作进程，等到了就从workerMap里删除，
            //没等到就下一次再来等
            foreach (array_keys($this->workerMap) as $workerPid) {
                $pid = pcntl_waitpid($workerPid, $status, WNOHANG);
                if ($pid == $workerPid) {
                    unset($this->workerMap[$workerPid]);
                }
            }
        } else {
            $pid = pcntl_wait($status, WNOHANG);

            //如果某个工作进程退出了，则启动一个新的工作进程
            if ($pid > 0) {
                $queueServiceName = $this->workerMap[$pid];
                unset($this->workerMap[$pid]);
                $this->startWorker($queueServiceName);
            }
        }
    }

    /**
     * 停止
     * 
     * 示例：
     *     ./console event:listen stop
     */
    private function stop() {
        if (!file_exists($this->pidFile)) {
            _throw('事件监听守护进程尚未运行');
        }

        $masterPid = file_get_contents($this->pidFile);
        posix_kill($masterPid, SIGTERM);

        //检查是否已停止运行
        while (file_exists($this->pidFile)) {
            sleep(3);
        }

        echo "事件监听守护进程已停止运行\n";
    }

    /**
     * 重启
     * 
     * 示例：
     *     ./console event:listen restart
     */
    private function restart() {
        $this->stop();

        echo "正在启动事件监听守护进程...\n";

        $this->start();
    }

}
