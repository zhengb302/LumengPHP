<?php

namespace LumengPHP\Job;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Messaging\Connection\Connection;

/**
 * Job执行程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobWorker {

    /**
     * @var string 此job worker对应的通道名称
     */
    private $channelName;

    /**
     * @var Connection 
     */
    private $messagingConn;

    /**
     * @var string 
     */
    private $queueName;

    /**
     * @var AppContext 应用程序上下文
     */
    private $appContext;

    /**
     * 构造一个JobWorker实例
     * @param string $channelName 通道名称
     * @param array $channelConfig 通道配置
     * @param AppContext $appContext 应用程序上下文
     */
    public function __construct($channelName, $channelConfig, AppContext $appContext) {
        $this->channelName = $channelName;
        $this->appContext = $appContext;

        $this->messagingConn = $this->appContext
                ->getService('messagingConnManager')
                ->getConnection($channelConfig['connectionName']);
        $this->queueName = $channelConfig['queueName'];
    }

    /**
     * 接收并执行对应通道上的job
     */
    public function execute() {
        for (;;) {
            $msg = $this->messagingConn->receive($this->queueName);
            $job = $msg->getPayload();
            $job->doJob();
        }
    }

}
