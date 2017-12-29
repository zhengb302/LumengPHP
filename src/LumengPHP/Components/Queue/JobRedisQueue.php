<?php

namespace LumengPHP\Components\Queue;

use Redis;

/**
 * 基于Redis的非阻塞型的Job队列
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class JobRedisQueue extends RedisQueue implements JobQueueInterface {

    /**
     * @var int 单次处理的最大Job数量
     */
    private $maxJobNum;

    /**
     * 构造一个<b>JobRedisQueue</b>实例
     * 
     * @param Redis $redis Redis实例
     * @param string $queueName redis队列名称
     * @param int $maxJobNum 单次处理的最大Job数量
     */
    public function __construct(Redis $redis, $queueName, $maxJobNum = 1000) {
        parent::__construct($redis, $queueName);
        $this->maxJobNum = $maxJobNum;
    }

    public function getMaxJobNum() {
        return $this->maxJobNum;
    }

}
