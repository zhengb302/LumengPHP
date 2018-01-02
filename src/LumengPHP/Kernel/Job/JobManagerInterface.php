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
     * @param string|null $jobQueueName 可选的Job队列名称。用于自定义Job队列，
     * 而不是使用Job系统规则选定的Job队列
     */
    public function delayJob($job, $jobQueueName = null);
}
