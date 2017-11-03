<?php

namespace LumengPHP\Console\Commands\Event;

use Exception;
use LumengPHP\Components\Queue\QueueInterface;
use LumengPHP\Console\InputInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Event\EventManagerInterface;

/**
 * 事件监听守护进程
 * 
 * 要求安装并启用了<b>PCNTL</b>和<b>POSIX</b>扩展
 * 
 * 根据事件配置，会为每一个事件队列开启一个工作进程(worker)，一个队列对应一个工作进程，多个事件可以共享一个队列。
 * 工作进程会监听队列里的事件数据，一旦有事件到达，便执行此事件的监听器，在执行完此事件所有的监听器之后，
 * 工作进程会继续监听事件数据，如果没有新的事件到达，工作进程将一直阻塞直到新的事件到达。
 * 
 * 注意：请使用阻塞型的队列，如果使用非阻塞型的队列，将会造成工作进程执行死循环代码，大量占用CPU资源。
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Listend {

    /**
     * @var AppContextInterface
     * @service
     */
    private $appContext;

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
     * @var bool 指示是否收到SIGTERM信号要退出运行
     */
    private $shouldExit = false;

    public function init() {
        $projectName = $this->getProjectName();
        $this->pidFile = "/var/run/{$projectName}/event-listend.pid";
    }

    /**
     * 返回当前项目的名称
     * 
     * @return string
     */
    private function getProjectName() {
        return basename($this->appContext->getRootDir());
    }

    public function execute() {
        if (posix_getuid() != 0) {
            _throw('您不是 root 用户，请在 root 用户下操作~');
        }

        /* @var $input InputInterface */
        $input = $this->appContext->getService('input');
        $action = $input->getArg(1);
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
     *     ./console event:listend
     *     ./console event:listend start
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
                $this->log('[master] 工作进程已经全部退出，结束运行，进程ID：' . getmypid());
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

        /* @var $classMetadataLoader ClassMetadataLoader */
        $classMetadataLoader = $this->appContext->getService('classMetadataLoader');

        $queueServices = [];
        foreach (array_keys($eventConfig) as $eventName) {
            $classMetadata = $classMetadataLoader->load($eventName);

            //如果不是队列化的异步事件
            if (!isset($classMetadata['classMetadata']['queued'])) {
                continue;
            }

            $queueServiceName = $classMetadata['classMetadata']['queued'];
            if ($queueServiceName === true) {
                $queueServiceName = 'defaultEventQueue';
            }

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
        //先检查一下运行用户配置情况
        list($userInfo, $groupInfo) = $this->checkUser();

        //
        umask(0);

        //fuck一下
        $pid = pcntl_fork();

        //创建子进程出错
        if ($pid < 0) {
            _throw('转为守护进程失败：创建子进程出错');
        }
        //父进程，退出
        elseif ($pid) {
            exit(0);
        }

        //使当前进程成为会话领导进程(从控制终端脱离)
        if (posix_setsid() == -1) {
            _throw('转为守护进程失败：设置当前进程为会话领导进程出错');
        }

        //切换工作目录到根目录
        chdir('/');

        //关闭标准输入、输出、错误，并都重定向到/dev/null
        //应用如果有输出日志消息的需求，那么最好定义自己的logger
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        fopen('/dev/null', 'r');
        fopen('/dev/null', 'a');
        fopen('/dev/null', 'a');

        //设置信号处理器
        $sigHandler = [$this, 'sigTermHandler'];
        pcntl_signal(SIGTERM, $sigHandler);
        pcntl_signal(SIGHUP, SIG_IGN);

        //切换运行用户和用户组。switchUser方法执行之前进程所属用户是root用户，
        //执行之后，就是某个低权限用户
        $this->switchUser($userInfo, $groupInfo);

        //把主进程ID写入pid文件中
        file_put_contents($this->pidFile, getmypid());

        //设置进程标题
        $projectName = $this->getProjectName();
        cli_set_process_title("{$projectName}.event-listend");

        $this->log('[master] 启动成功，进程ID：' . getmypid());
    }

    /**
     * 先检查一下运行用户配置情况
     * 
     * @throws Exception 如果检查失败，则抛出异常
     * @return array 成功的情况下会返回用户及用户组信息，格式：[用户信息, 用户组信息]
     */
    private function checkUser() {
        //事件监听守护进程的配置(跟事件及其监听器的配置是两码事)
        $config = $this->appContext->getConfig('eventListend') ?: [];
        $username = isset($config['user']) ? $config['user'] : 'nobody';
        $groupName = isset($config['group']) ? $config['group'] : 'nobody';

        $userInfo = posix_getpwnam($username);
        if (!$userInfo) {
            _throw("用户“{$username}”不存在，启动失败");
        }

        $groupInfo = posix_getgrnam($groupName);
        if (!$groupInfo) {
            _throw("用户组“{$groupName}”不存在，启动失败");
        }

        return [$userInfo, $groupInfo];
    }

    /**
     * 切换运行用户和用户组
     */
    private function switchUser($userInfo, $groupInfo) {
        //准备PID文件目录，并变更其所属的用户和用户组。此时还是root用户。
        $pidDir = dirname($this->pidFile);
        if (!is_dir($pidDir)) {
            mkdir($pidDir, 0755);
        }
        chown($pidDir, $userInfo['uid']);
        chgrp($pidDir, $groupInfo['gid']);

        //切换用户
        posix_setuid($userInfo['uid']);
        posix_seteuid($userInfo['uid']);
        posix_setgid($groupInfo['gid']);
        posix_setegid($groupInfo['gid']);
    }

    /**
     * SIGTERM信号处理器
     * 
     * @param int $signo
     */
    private function sigTermHandler($signo) {
        //主进程收到SIGTERM信号，设置标志，挨个向工作进程发送SIGTERM信号
        if ($this->isMaster) {
            $this->log('[master] 收到SIGTERM信号，向各个工作进程发送SIGTERM信号');

            $this->shouldExit = true;
            foreach (array_keys($this->workerMap) as $workPid) {
                posix_kill($workPid, SIGTERM);
            }
        }
        //工作进程收到SIGTERM信号，退出运行
        else {
            $this->log('[worker] 收到SIGTERM信号，退出运行，进程ID：' . getmypid());
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
            $this->log("[master] 创建工作进程失败，队列服务名称：{$queueServiceName}", 'error');
        }
        //父进程
        else if ($pid) {
            $this->workerMap[$pid] = $queueServiceName;
        }
        //子进程
        else {
            $this->isMaster = false;

            $this->log("[worker] 启动成功，进程ID：" . getmypid() . "，队列服务名称：{$queueServiceName}");

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
        /* @var $eventManager EventManagerInterface */
        $eventManager = $this->appContext->getService('eventManager');

        while (true) {
            while (true) {
                //出队并判断会不会是因为超时返回，如果是超时返回(返回值为null)，则继续监听队列；
                //如果不是超时而是异步事件到达(返回值不为null)，则执行其对应的事件监听器
                $event = $queueService->dequeue();
                if (is_null($event)) {
                    break;
                }

                $eventManager->trigger($event, true);

                pcntl_signal_dispatch();
            }

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

                    $exitReason = $this->parseExitReason($status);
                    $this->log("[master] 工作进程“{$workerPid}”已退出运行，{$exitReason}");
                }
            }
        } else {
            $pid = pcntl_wait($status, WNOHANG);

            //如果某个工作进程退出了，则启动一个新的工作进程
            if ($pid > 0) {
                $queueServiceName = $this->workerMap[$pid];
                unset($this->workerMap[$pid]);

                $exitReason = $this->parseExitReason($status);
                $this->log("[master] 工作进程“{$pid}”已退出运行，{$exitReason}，队列服务名称：{$queueServiceName}，即将启动一个新的工作进程");

                $this->startWorker($queueServiceName);
            }
        }
    }

    /**
     * 解析工作进程退出原因
     * 
     * @param int $status
     * @return string
     */
    private function parseExitReason($status) {
        if (pcntl_wifexited($status)) {
            $exitCode = pcntl_wexitstatus($status);
            return "退出原因：正常退出，退出状态码：{$exitCode}";
        } elseif (pcntl_wifsignaled($status)) {
            $sigNum = pcntl_wtermsig($status);
            return "退出原因：被信号终止，信号代号：{$sigNum}";
        }

        return '退出原因：未知';
    }

    /**
     * 记录运行日志
     * 
     * @param string $msg
     * @param string $level 日志级别：info、error、warning
     */
    private function log($msg, $level = 'info') {
        static $logFile = null;
        if (is_null($logFile)) {
            $logDir = $this->appContext->getRuntimeDir() . '/log';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            $logFilePath = "$logDir/event-listend.log";
            $logFile = fopen($logFilePath, 'a');
        }

        $time = date('Y-m-d H:i:s');
        $logData = "[{$level}] [{$time}] {$msg}\n";
        fwrite($logFile, $logData);
    }

    /**
     * 停止
     * 
     * 示例：
     *     ./console event:listend stop
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
     *     ./console event:listend restart
     */
    private function restart() {
        $this->stop();

        echo "正在启动事件监听守护进程...\n";

        $this->start();
    }

}
