<?php

namespace LumengPHP\Components\Lock;

use Redis;

/**
 * 基于Redis的锁
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class RedisLock implements LockInterface {

    /**
     * @var string 锁名称
     */
    private $lockName;

    /**
     *
     * @var int 锁过期时间，以秒为单位
     */
    private $duration;

    /**
     * @var Redis 
     */
    private $redis;

    public function __construct($lockName, $duration, Redis $redis) {
        $this->lockName = $lockName;
        $this->duration = $duration;

        $this->redis = $redis;
    }

    public function acquire() {
        $result = $this->redis->get($this->lockName);
        if ($result) {
            return false;
        }

        $this->redis->set($this->lockName, 1, $this->duration);
        return true;
    }

    public function release() {
        $this->redis->del($this->lockName);
    }

}
