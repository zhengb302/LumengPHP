<?php

namespace LumengPHP\Components\Lock;

/**
 * 基于数据库的锁
 * 
 * 数据库表结构：
 * CREATE TABLE `lock` (
 *  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 *  `lock_name` varchar(100) NOT NULL COMMENT '锁名称',
 *  `add_time` int(10) unsigned NOT NULL COMMENT '创建锁的时间',
 *  `expire_time` int(10) unsigned NOT NULL COMMENT '锁过期时间',
 *  PRIMARY KEY (`id`),
 *  UNIQUE KEY `lock_name` (`lock_name`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基于数据库的锁';
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class DbLock implements LockInterface {

    /**
     * @var string 锁名称
     */
    private $lockName;

    /**
     * @var int 锁过期时间，以秒为单位
     */
    private $duration;

    /**
     * @var LockModel 
     */
    private $lockModel;

    public function __construct($lockName, $duration) {
        $this->lockName = $lockName;
        $this->duration = $duration;

        $this->lockModel = new LockModel();
    }

    /**
     * 获取锁
     * 
     * @return bool 返回true，表示获取成功；返回false，表示获取失败
     */
    public function acquire() {
        $lock = $this->lockModel->where(['lock_name' => $this->lockName])->find();

        //锁不存在
        if (!$lock) {
            $this->addLock();
            return true;
        }

        //锁过期了
        $now = time();
        if ($lock['expire_time'] <= $now) {
            //删除过期的锁
            $this->lockModel->where(['lock_name' => $this->lockName])->delete();

            //加新锁
            $this->addLock();

            return true;
        }

        //锁存在，且也没过期
        return false;
    }

    /**
     * 加锁
     */
    private function addLock() {
        $now = time();
        $this->lockModel->add([
            'lock_name' => $this->lockName,
            'add_time' => $now,
            'expire_time' => $now + $this->duration,
        ]);
    }

    /**
     * 释放锁
     */
    public function release() {
        $this->lockModel->where(['lock_name' => $this->lockName])->delete();
    }

}
