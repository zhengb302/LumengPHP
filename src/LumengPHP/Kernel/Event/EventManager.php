<?php

namespace LumengPHP\Kernel\Event;

use LumengPHP\Components\Queue\QueueInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
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
     * @var object 当前事件对象
     */
    private $currentEvent;

    public function __construct(array $eventConfig, AppContextInterface $appContext, ClassInvoker $classInvoker) {
        $this->eventConfig = $eventConfig;
        $this->appContext = $appContext;
        $this->classInvoker = $classInvoker;
    }

    public function trigger($event) {
        $this->currentEvent = $event;

        $refObj = new ReflectionClass($event);
        $eventName = $refObj->getName();

        //如果当前事件未注册监听器，那么直接退出
        if (!isset($this->eventConfig[$eventName])) {
            return;
        }

        //如果是队列化的异步事件，则把事件对象序列化之后放入队列中，然后直接返回
        $metadataLoader = new ClassMetadataLoader($this->appContext, $refObj);
        $classMetadata = $metadataLoader->load();
        if (isset($classMetadata['queued'])) {
            $queueServiceName = $classMetadata['queued'] ?: 'defaultEventQueue';
            /* @var $queueService QueueInterface */
            $queueService = $this->appContext->getService($queueServiceName);
            $queueService->enqueue($event);
            return;
        }

        //如果是未队列化的同步事件，则逐个执行事件监听器
        $listeners = $this->eventConfig[$eventName];
        foreach ($listeners as $listener) {
            $return = $this->classInvoker->invoke($listener);

            //如果某个事件监听器返回“false”，则停止事件的传播
            if ($return === false) {
                break;
            }
        }
    }

    public function getCurrentEvent() {
        return $this->currentEvent;
    }

}
