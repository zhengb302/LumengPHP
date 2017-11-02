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
    private $timeout = 5;

    /**
     * 构造一个<b>BlockingRedisQueue</b>实例
     * 
     * @param Redis $redis Redis实例
     * @param string $queueName redis队列名称
     */
    public function __construct(Redis $redis, $queueName) {
        $this->redis = $redis;
        $this->queueName = $queueName;
    }

    /**
     * 设置超时时间
     * 
     * @param int $timeout 超时时间，以秒为单位，为0表示永远等待不超时。
     * 但是要注意phpredis扩展底层的socket连接会超时(当前超时时间是60秒)，且超时的时候会抛出异常。
     * 所以这个超时时间最好设置为小于60秒且不为0的一个值。
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function enqueue($element) {
        $this->redis->lPush($this->queueName, serialize($element));
    }

    public function dequeue() {
        $return = $this->redis->brPop($this->queueName, $this->timeout);

        //阻塞型的 pop 超时会返回一个空数组(而不是null或false)，这里用 empty 来判断
        if (empty($return)) {
            return null;
        }

        $data = $return[1];
        return unserialize($data);
    }

}
