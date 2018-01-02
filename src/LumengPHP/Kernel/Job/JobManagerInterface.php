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
     * @param string|null $jobQueueName Job队列名称
     */
    public function delayJob($job, $jobQueueName = null);
}
