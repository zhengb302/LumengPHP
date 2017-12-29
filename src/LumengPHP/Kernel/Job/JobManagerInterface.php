<?php

namespace LumengPHP\Kernel\Job;

/**
 * Job管理接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface JobManagerInterface {

    /**
     * 延迟执行Job
     * 
     * @param object $job Job对象
     */
    public function delayJob($job);
}
