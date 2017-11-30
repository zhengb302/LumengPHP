<?php

namespace LumengPHP\Components\Queue;

use Redis;

/**
 * 基于Redis的非阻塞型的队列
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class RedisQueue implements QueueInterface {

    /**
     * @var Redis 
     */
    private $redis;

    /**
     * @var string 队列名称
     */
    private $queueName;

    /**
     * 构造一个<b>RedisQueue</b>实例
     * 
     * @param Redis $redis Redis实例
     * @param string $queueName redis队列名称
     */
    public function __construct(Redis $redis, $queueName) {
        $this->redis = $redis;
        $this->queueName = $queueName;
    }

    public function enqueue($element) {
        $this->redis->lPush($this->queueName, serialize($element));
    }

    public function dequeue() {
        $data = $this->redis->rPop($this->queueName);

        //如果队列里没数据了
        if ($data === false) {
            return null;
        }

        return unserialize($data);
    }

}
