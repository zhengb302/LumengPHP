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
 * 根据事件配置，会为每一个事件队列开启一个子进程，一个队列对应一个子进程，多个事件可以共享一个队列。
 * 子进程会监听队列里的事件数据，一旦有事件到达，便执行此事件的监听器，执行完此事件所有的监听器之后，
 * 子进程会继续监听事件数据，如果没有新的事件到达，要么阻塞(使用了阻塞型的队列)，要么退出(使用了非阻塞型的队列或阻塞超时)。
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
     * @var EventManagerInterface 
     * @service
     */
    private $eventManager;

    /**
     * @var string pid文件路径
     */
    private $pidFile;

    /**
     * @var array 工作进程Map，格式：工作进程ID => 事件队列服务对象名称
     */
    private $workerMap = [];

    public function init() {
        $projectName = basename($this->appContext->getRootDir());
        $this->pidFile = "/var/run/{$projectName}.pid";
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

        foreach ($queueServices as $queueServiceName) {
            $this->startWorker($queueServiceName);
        }

        //监控工作进程
        $this->monitorWorker();
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

        //关闭所有已打开的文件描述符
        for ($i = 0; $i < 1024; $i++) {
            fclose($i);
        }

        //设置信号处理器
        $sigHandler = [$this, 'sigHandler'];
        pcntl_signal(SIGTERM, $sigHandler);
        pcntl_signal(SIGHUP, $sigHandler);

        //已成功转为守护进程，把主进程ID写入pid文件中
        file_put_contents($this->pidFile, getmypid());
    }

    /**
     * 信号处理器
     * 
     * @param int $signo
     */
    private function sigHandler($signo) {
        switch ($signo) {
            //关闭
            case SIGTERM:
                $this->stop();
                exit;
                break;
            //重启
            case SIGHUP:
                $this->restart();
                break;
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
        }
    }

    /**
     * 监控工作进程
     * 
     * 如果某个工作进程退出了，则启动一个新的工作进程
     */
    private function monitorWorker() {
        while (true) {
            $status = 0;
            $workerPid = pcntl_wait($status);
            $queueServiceName = $this->workerMap[$workerPid];

            unset($this->workerMap[$workerPid]);

            $this->startWorker($queueServiceName);
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
    }

    /**
     * 重启
     * 
     * 示例：
     *     ./console event:listen restart
     */
    private function restart() {
        $this->stop();
        $this->start();
    }

}
