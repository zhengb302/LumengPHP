<?php

namespace LumengPHP\Job;

use LumengPHP\Messaging\Connection\ConnectionInterface;
use LumengPHP\Messaging\Message;

/**
 * Job派发程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobDispatcher {

    /**
     * @var ConnectionInterface 底层消息服务连接
     */
    private $messagingConn;

    /**
     * @var string 队列名称
     */
    private $queue;

    public function __construct(ConnectionInterface $messagingConn, $queue) {
        $this->messagingConn = $messagingConn;
        $this->queue = $queue;
    }

    public function dispatch(JobInterface $job) {
        $this->messagingConn->send($this->queue, new Message($job));
    }

}
