<?php

namespace LumengPHP\Kernel\Event;

/**
 * 事件管理器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface EventManagerInterface {

    /**
     * 触发一个事件
     * 
     * @param object $event 事件对象
     * @param bool $immediately 是否立即触发。如果此参数为<b>true</b>，对于队列化的异步事件，
     * 则不会把该事件放入队列中，而是立即执行其监听器；对于未队列化的同步事件，此参数不起任何作用。
     */
    public function trigger($event, $immediately = false);

    /**
     * 获取当前事件
     * 
     * @return object 当前事件对象
     */
    public function getCurrentEvent();
}
