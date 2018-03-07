<?php

namespace LumengPHP\Components\Lock;

/**
 * 基于文件的锁
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class FileLock {

    /**
     * @var string 锁文件夹
     */
    private $lockFile;

    /**
     * @var int 锁过期时间，以秒为单位。如果为0，则表示永不过期。
     */
    private $duration;

    /**
     * 构造一个<b>FileLock</b>实例
     * 
     * @param string $lockName 锁名称
     * @param int $duration 锁过期时间，以秒为单位。如果为0，则表示永不过期。
     */
    public function __construct($lockName, $duration = 0) {
        $lockDir = app_context()->getRuntimeDir() . '/lock';
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755);
        }

        $this->lockFile = $lockDir . '/' . $lockName;
        $this->duration = $duration;
    }

    public function acquire() {
        //检查锁是否已经存在，如果不存在，则加锁，本次加锁成功
        $exists = file_exists($this->lockFile);
        if (!$exists) {
            $this->addLock();
            return true;
        }

        /*
         * 以下是锁已经存在的情况
         */

        $expireTime = file_get_contents($this->lockFile);

        //当前锁永不过期，本次加锁失败
        if ($expireTime == 0) {
            return false;
        }

        //当前锁已过期，删除过期的锁，加新锁，本次加锁成功
        if ($expireTime <= time()) {
            unlink($this->lockFile);
            $this->addLock();
            return true;
        }
        //当前锁没过期，本次加锁失败
        else {
            return false;
        }
    }

    private function addLock() {
        //过期时间。如果为0，则表示永不过期
        $expireTime = $this->duration == 0 ? 0 : (time() + $this->duration);

        file_put_contents($this->lockFile, $expireTime);
    }

    public function release() {
        unlink($this->lockFile);
    }

}
