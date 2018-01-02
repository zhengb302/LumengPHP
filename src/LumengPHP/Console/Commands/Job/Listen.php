<?php

namespace LumengPHP\Console\Commands\Job;

use LumengPHP\Components\Queue\JobQueueInterface;
use LumengPHP\Kernel\AppContextInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * 任务监听
 * 
 * 要求安装并启用了<b>PCNTL</b>扩展
 * 
 * CRON定时调用(每分钟)任务监听入口，根据任务队列配置，会为每一个任务队列开启一个工作进程(worker)，
 * 一个队列对应一个工作进程，多个任务可以共享一个任务队列。工作进程会弹出队列里的任务数据，
 * 如果有任务数据，便执行此任务，在执行完此任务之后，继续弹出下一个任务数据，
 * 如果此时任务队列为空或已监听的任务数量达到上限，则工作进程退出执行。
 * 在所有的工作进程退出执行之后，主进程也退出。
 * 
 * 注意：请使用非阻塞型的队列，如果使用阻塞型的队列，将会造成工作进程一直等待。
 * 
 * 配置示例：
 * <pre>
 * [
 *     'jobListen' => [
 *         'logger' => 'service name of logger',
 *         'disableMultiWorkers' => false,
 *     ],
 * ]
 * </pre>
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
     * @var LoggerInterface 日志组件
     */
    private $logger;

    /**
     * @var bool 是否禁用了多进程模式
     */
    private $disableMultiWorkers;

    /**
     * @var array 工作进程Map，格式：工作进程ID => 任务队列名称
     */
    private $workerMap = [];

    public function init() {
        $config = $this->appContext->getConfig('jobListen') ?: [];

        if (isset($config['logger']) && $config['logger']) {
            $this->logger = $this->appContext->getService($config['logger']);
        } else {
            $this->logger = new NullLogger();
        }

        $this->disableMultiWorkers = isset($config['disableMultiWorkers']) ? $config['disableMultiWorkers'] : false;
    }

    public function execute() {
        //检查锁文件
        $lockFile = $this->appContext->getRuntimeDir() . '/job-listen.lock';
        if (file_exists($lockFile)) {
            _throw('任务监听任务已经在运行中');
        }

        $jobQueues = $this->extraJobQueues();
        if (empty($jobQueues)) {
            _throw('没有需要队列化的异步任务');
        }

        //创建锁文件
        touch($lockFile);

        $this->logger->info('[master] 开始执行任务监听任务，进程ID：' . getmypid());

        //非多进程模式，直接监听各队列
        if ($this->disableMultiWorkers) {
            $this->listenQueues($jobQueues);
        }
        //多进程模式，启动各工作进程
        else {
            $this->startWorkers($jobQueues);
        }

        //运行结束，删除锁文件，解锁
        unlink($lockFile);
    }

    /**
     * 提取出所有的任务队列名称
     * 
     * @return array
     */
    private function extraJobQueues() {
        $jobQueueConfig = $this->appContext->getAppSetting()->getJobQueues() ?: [];
        return array_keys($jobQueueConfig) ?: [];
    }

    /**
     * 非多进程模式，直接监听各队列
     * 
     * @param array $jobQueues
     */
    private function listenQueues($jobQueues) {
        //逐个监听各任务队列
        foreach ($jobQueues as $jobQueueName) {
            $this->listenQueue($jobQueueName);
        }

        $this->logger->info('[master] 已全部监听完毕，即将结束运行，进程ID：' . getmypid());
    }

    /**
     * 多进程模式，启动各工作进程
     * 
     * @param array $jobQueues
     */
    private function startWorkers($jobQueues) {
        //为每个任务队列分别开启一个工作进程
        foreach ($jobQueues as $jobQueueName) {
            $this->startWorker($jobQueueName);
        }

        while (true) {
            //如果工作进程已经全部退出
            if (empty($this->workerMap)) {
                $this->logger->info('[master] 工作进程已经全部退出，即将结束运行，进程ID：' . getmypid());
                return;
            }

            $this->waitWorker();

            sleep(1);
        }
    }

    /**
     * 启动工作进程
     * 
     * @param string $jobQueueName
     */
    private function startWorker($jobQueueName) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            $this->logger->error("[master] 创建工作进程失败，队列名称：{$jobQueueName}，进程ID：" . getmypid());
        }
        //父进程
        else if ($pid) {
            $this->workerMap[$pid] = $jobQueueName;
        }
        //子进程
        else {
            $this->logger->info("[worker] 启动成功，进程ID：" . getmypid() . "，队列名称：{$jobQueueName}");

            $this->listenQueue($jobQueueName);

            //这里必须退出，不然就跑去执行主进程的代码了
            exit(0);
        }
    }

    /**
     * 监听队列
     * 
     * @param string $jobQueueName 队列服务名称
     */
    private function listenQueue($jobQueueName) {
        /* @var $jobQueue JobQueueInterface */
        $jobQueue = $this->appContext->getService($jobQueueName);

        //记录开始时间
        $startTime = time();

        $maxJobNum = $jobQueue->getMaxJobNum();
        for ($i = 0; $i < $maxJobNum; $i++) {
            $job = $jobQueue->dequeue();
            if (is_null($job)) {
                break;
            }

            //执行任务
            $job->execute();
        }

        //处理这些任务的耗时，以秒为单位
        $timeUsed = (time() - $startTime) . 's';

        if ($this->disableMultiWorkers) {
            $msg = "[master] 已执行{$i}个任务，用时：{$timeUsed}，队列名称：{$jobQueueName}";
        } else {
            $msg = "[worker] 已执行{$i}个任务，用时：{$timeUsed}，进程ID：" . getmypid() .
                    "，队列名称：{$jobQueueName}，即将退出运行";
        }
        $this->logger->info($msg);
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
            $jobQueueName = $this->workerMap[$pid];
            unset($this->workerMap[$pid]);

            $exitReason = $this->parseExitReason($status);
            $this->logger->info("[master] 工作进程“{$pid}”已退出运行，{$exitReason}，队列名称：{$jobQueueName}");
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

}
