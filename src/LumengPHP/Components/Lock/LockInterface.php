<?php

namespace LumengPHP\Components\Lock;

/**
 * 锁接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface LockInterface {

    /**
     * 获取锁
     * 
     * @return bool 返回true，表示获取成功；返回false，表示获取失败
     */
    public function acquire();

    /**
     * 释放锁
     */
    public function release();
}
