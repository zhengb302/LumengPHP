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
     * @param string|object $event 事件名称，或事件对象
     */
    public function trigger($event);

    /**
     * 获取当前事件
     * 
     * @return string|object 事件名称，或事件对象，取决于触发事件时传递的参数值
     */
    public function getCurrentEvent();
}
