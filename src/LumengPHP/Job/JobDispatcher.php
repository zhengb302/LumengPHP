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

    public function dispatch(JobInterface $job) {
        $msg = new Message($job);
        $this->messagingConn->send($queue, $msg);
    }

}
