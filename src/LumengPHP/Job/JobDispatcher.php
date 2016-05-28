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
     * @var ConnectionInterface 底层消息服务连接
     */
    private $messagingConn;

    public function __construct(ConnectionManager $messagingConn) {
        $this->messagingConn = $messagingConn;
    }

    public function dispatch(JobInterface $job) {
        $this->messagingConn->send($this->queue, new Message($job));
    }

}
