<?php

namespace LumengPHP\Kernel\Job;

/**
 * 事件Job - 用于处理队列化的异步事件
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class EventJob {

    /**
     * @var object 事件对象
     */
    private $event;

    /**
     * 构造一个<b>EventJob</b>实例
     * 
     * @param object $event 事件对象
     */
    public function __construct($event) {
        $this->event = $event;
    }

    public function execute() {
        event_manager()->trigger($this->event, true);
    }

}
