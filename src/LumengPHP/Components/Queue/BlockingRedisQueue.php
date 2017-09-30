<?php

namespace LumengPHP\Components\Queue;

use Redis;

/**
 * 基于Redis的阻塞型的队列
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class BlockingRedisQueue implements QueueInterface {

    /**
     * @var Redis 
     */
    private $redis;

    /**
     * @var string 队列名称
     */
    private $queueName;

    /**
     * @var int 超时时间，以秒为单位
     */
    private $timeout = 0;

    public function __construct(Redis $redis, $queueName) {
        $this->redis = $redis;
        $this->queueName = $queueName;
    }

    /**
     * 设置超时时间
     * 
     * @param int $timeout 超时时间，以秒为单位。为0表示永远等待不超时。
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function enqueue($element) {
        $this->redis->lPush($this->queueName, serialize($element));
    }

    public function dequeue() {
        $return = $this->redis->brPop($this->queueName, $this->timeout);

        //超时返回
        if (is_null($return)) {
            return null;
        }

        $data = $return[1];
        return unserialize($data);
    }

}
