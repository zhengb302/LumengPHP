<?php

namespace LumengPHP\Kernel\Job;

use LumengPHP\Components\Queue\JobQueueInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\AppContextInterface;
use ReflectionClass;

/**
 * Job管理器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class JobManager implements JobManagerInterface {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var array Job队列配置
     */
    private $jobQueueConfig;

    /**
     * @var ClassMetadataLoader 类元数据加载程序
     */
    private $classMetadataLoader;

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
        $this->jobQueueConfig = $appContext->getAppSetting()->getJobQueues();
        $this->classMetadataLoader = $appContext->getService('classMetadataLoader');
    }

    public function delayJob($job, $jobQueueName = '') {
        if (!$jobQueueName) {
            $jobRefObj = new ReflectionClass($job);
            $jobClass = $jobRefObj->getName();
            $classMetadata = $this->classMetadataLoader->load($jobClass);
            $jobQueueName = isset($classMetadata['classMetadata']['queue']) ?
                    $classMetadata['classMetadata']['queue'] :
                    'defaultJobQueue';
        }

        if (!isset($this->jobQueueConfig[$jobQueueName])) {
            _throw("任务队列不存在，队列名称：{$jobQueueName}");
        }

        /* @var $jobQueue JobQueueInterface */
        $jobQueue = $this->appContext->getService($jobQueueName);
        $jobQueue->enqueue($job);
    }

}
