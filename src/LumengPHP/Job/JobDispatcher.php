<?php

namespace LumengPHP\Job;

use LumengPHP\Messaging\Connection\ConnectionManager;
use LumengPHP\Messaging\Message;

/**
 * Job派发程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobDispatcher {

    /**
     * @var ConnectionManager 消息服务连接管理器
     */
    private $connManager;

    /**
     * @var array 通道配置，格式：通道名称 => 通道配置
     */
    private $channels;

    /**
     * @var string 默认通道名称
     */
    private $defaultChannelName;

    /**
     * @var array "job => 通道名称" 映射
     */
    private $jobChannel;

    public function __construct(ConnectionManager $connManager, array $jobConfig) {
        $this->connManager = $connManager;

        $this->channels = $jobConfig['channels'];
        $this->defaultChannelName = array_keys($this->channels)[0];
        $this->jobChannel = $jobConfig['jobChannel'];
    }

    public function dispatch(JobInterface $job) {
        $jobName = $job->getName();
        $channelName = isset($this->jobChannel[$jobName]) ?
                $this->jobChannel[$jobName] :
                $this->defaultChannelName;
        $channelConfig = $this->channels[$channelName];

        //消息连接名称
        $connName = $channelConfig['connectionName'];

        //消息连接实例
        $conn = $this->connManager->getConnection($connName);

        $queue = $channelConfig['queueName'];

        $conn->send($queue, new Message($job));
    }

}
