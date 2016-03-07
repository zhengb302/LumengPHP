<?php

namespace LumengPHP\Kernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use LumengPHP\Kernel\AppContext;

/**
 * 命令初始化事件订阅
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CommandInitializationListener implements EventSubscriberInterface {

    /**
     * @var AppContext 
     */
    private $appContext;

    public function __construct(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function onKernelController(FilterControllerEvent $event) {
        $controller = $event->getController();
        $cmdObject = $controller[0];
        $cmdObject->init($this->appContext);
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::CONTROLLER => array(array('onKernelController', 0)),
        );
    }

}
