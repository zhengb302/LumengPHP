<?php

namespace LumengPHP\Components\Queue;

/**
 * Job队列接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface JobQueueInterface extends QueueInterface {

    /**
     * 返回单次处理的最大Job数量
     * 
     * @return int
     */
    public function getMaxJobNum();
}
