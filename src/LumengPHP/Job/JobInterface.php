<?php

namespace LumengPHP\Job;

/**
 * Job接口
 * 
 * @author Lumeng <zhengb302@163.com>
 */
interface JobInterface {

    /**
     * 返回job名称
     * @return string job名称
     */
    public function getName();

    /**
     * 执行job
     */
    public function doJob();

    /**
     * 释放该job
     */
    public function release();
}
