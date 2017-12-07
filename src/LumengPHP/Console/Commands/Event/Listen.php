<?php

namespace LumengPHP\Console\Commands\Event;

use LumengPHP\Components\Queue\QueueInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Event\EventManagerInterface;

/**
 * 事件监听
 * 
 * 要求安装并启用了<b>PCNTL</b>扩展
 * 
 * CRON定时调用(每分钟)事件监听入口，根据事件配置，会为每一个事件队列开启一个工作进程(worker)，
 * 一个队列对应一个工作进程，多个事件可以共享一个队列。工作进程会弹出队列里的事件数据，
 * 如果有事件数据，便执行此事件的监听器，在执行完此事件所有的监听器之后，继续弹出下一个事件数据，
 * 如果此时事件队列为空或已监听的事件数量达到上限，则工作进程退出执行。
 * 在所有的工作进程退出执行之后，主进程也退出。
 * 
 * 注意：请使用非阻塞型的队列，如果使用阻塞型的队列，将会造成工作进程一直等待。
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
     * @var bool 是否禁用了多进程模式
     */
    private $disableMultiWorkers;

    /**
     * @var int 每次处理的最大事件数量
     */
    private $maxEventNum;

    /**
     * @var array 工作进程Map，格式：工作进程ID => 事件队列服务对象名称
     */
    private $workerMap = [];

    public function init() {
        $config = $this->appContext->getConfig('eventListen') ?: [];
        $this->disableMultiWorkers = isset($config['disableMultiWorkers']) ? $config['disableMultiWorkers'] : false;
        $this->maxEventNum = $config['maxEventNum'] ?: 1000;
    }

    public function execute() {
        //检查锁文件
        $lockFile = $this->appContext->getRuntimeDir() . '/event-listen.lock';
        if (file_exists($lockFile)) {
            _throw('事件监听任务已经在运行中');
        }

        $queueServices = $this->extraQueueServices();
        if (empty($queueServices)) {
            _throw('没有需要队列化的异步事件');
        }

        //创建锁文件
        touch($lockFile);

        $this->log('[master] 开始执行事件监听任务，进程ID：' . getmypid());

        //非多进程模式，直接监听各队列
        if ($this->disableMultiWorkers) {
            $this->listenQueues($queueServices);
        }
        //多进程模式，启动各工作进程
        else {
            $this->startWorkers($queueServices);
        }

        //运行结束，删除锁文件，解锁
        unlink($lockFile);
    }

    /**
     * 提取出所有的事件队列名称
     * 
     * @return array
     */
    private function extraQueueServices() {
        $eventConfig = $this->appContext->getAppSetting()->getEvents();
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
     * 非多进程模式，直接监听各队列
     * 
     * @param array $queueServices
     */
    private function listenQueues($queueServices) {
        //逐个监听各事件队列
        foreach ($queueServices as $queueServiceName) {
            $this->listenQueue($queueServiceName);
        }

        $this->log('[master] 已全部监听完毕，即将结束运行，进程ID：' . getmypid());
    }

    /**
     * 多进程模式，启动各工作进程
     * 
     * @param array $queueServices
     */
    private function startWorkers($queueServices) {
        //为每个事件队列分别开启一个工作进程
        foreach ($queueServices as $queueServiceName) {
            $this->startWorker($queueServiceName);
        }

        while (true) {
            //如果工作进程已经全部退出
            if (empty($this->workerMap)) {
                $this->log('[master] 工作进程已经全部退出，即将结束运行，进程ID：' . getmypid());
                return;
            }

            $this->waitWorker();

            sleep(1);
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
            $this->log("[worker] 启动成功，进程ID：" . getmypid() . "，队列服务名称：{$queueServiceName}");

            $this->listenQueue($queueServiceName);

            //这里必须退出，不然就跑去执行主进程的代码了
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

        //记录开始时间
        $startTime = time();

        for ($i = 0; $i < $this->maxEventNum; $i++) {
            $event = $queueService->dequeue();
            if (is_null($event)) {
                break;
            }

            $eventManager->trigger($event, true);
        }

        //处理这些事件的耗时，以秒为单位
        $timeUsed = (time() - $startTime) . 's';

        if ($this->disableMultiWorkers) {
            $msg = "[master] 已监听{$i}个事件，用时：{$timeUsed}，队列服务名称：{$queueServiceName}";
        } else {
            $msg = "[worker] 已监听{$i}个事件，用时：{$timeUsed}，进程ID：" . getmypid() .
                    "，队列服务名称：{$queueServiceName}，即将退出运行";
        }
        $this->log($msg);
    }

    /**
     * 等待工作进程
     */
    private function waitWorker() {
        if (empty($this->workerMap)) {
            return;
        }

        $status = 0;
        $pid = pcntl_wait($status, WNOHANG);

        //如果某个工作进程退出了
        if ($pid > 0) {
            $queueServiceName = $this->workerMap[$pid];
            unset($this->workerMap[$pid]);

            $exitReason = $this->parseExitReason($status);
            $this->log("[master] 工作进程“{$pid}”已退出运行，{$exitReason}，队列服务名称：{$queueServiceName}");
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
            $logFilePath = "$logDir/event-listen.log";
            $logFile = fopen($logFilePath, 'a');
        }

        $time = date('Y-m-d H:i:s');
        $logData = "[{$level}] [{$time}] {$msg}\n";
        fwrite($logFile, $logData);
    }

}
