<?php

namespace LumengPHP\Kernel\Event;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\ClassInvoker;
use ReflectionClass;

/**
 * 事件管理器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class EventManager implements EventManagerInterface {

    /**
     * @var array 事件配置，格式：事件类的全限定名称 => 事件监听器列表
     */
    private $eventConfig;

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var ClassInvoker 
     */
    private $classInvoker;

    /**
     * @var string|object 事件名称，或事件对象，取决于触发事件时传递的参数值
     */
    private $currentEvent;

    public function __construct(array $eventConfig, AppContextInterface $appContext, ClassInvoker $classInvoker) {
        $this->eventConfig = $eventConfig;
        $this->appContext = $appContext;
        $this->classInvoker = $classInvoker;
    }

    public function trigger($event) {
        $this->currentEvent = $event;

        if (is_string($event)) {
            $eventName = $event;
        } else {
            $refObj = new ReflectionClass($event);
            $eventName = $refObj->getName();
        }

        //如果当前事件未注册监听器，那么直接退出
        if (!isset($this->eventConfig[$eventName])) {
            return;
        }

        $listeners = $this->eventConfig[$eventName];
        foreach ($listeners as $listener) {
            $return = $this->classInvoker->invoke($listener);

            //如果某个事件监听器返回的是“false”，则停止事件的传播
            if ($return === false) {
                break;
            }
        }
    }

    public function getCurrentEvent() {
        return $this->currentEvent;
    }

}
