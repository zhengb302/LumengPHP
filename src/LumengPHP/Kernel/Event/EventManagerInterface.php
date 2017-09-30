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
     */
    public function trigger($event);

    /**
     * 获取当前事件
     * 
     * @return object 当前事件对象
     */
    public function getCurrentEvent();
}
