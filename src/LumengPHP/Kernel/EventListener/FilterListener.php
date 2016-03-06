<?php

namespace LumengPHP\Kernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

/**
 * Filter组件相关事件
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FilterListener implements EventSubscriberInterface {

    /**
     * @var array 
     */
    private $filterConfig;

    public function __construct(array $filterConfig) {
        $this->filterConfig = $filterConfig;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();

        if (!$request->attributes->has('_cmd')) {
            // 必须在路由组件执行完之后
            trigger_error('Filter should execute after Routing.', E_USER_ERROR);
            return;
        }

        $preFilterConfigs = $this->filterConfig['preFilters'];
        if (empty($preFilterConfigs)) {
            return;
        }

        foreach ($preFilterConfigs as $name => $preFilterConfig) {
            $class = $preFilterConfig['class'];
            $filter = new $class();
            $response = $filter->doFilter();
            if (!is_null($response) && $response instanceof Response) {
                $event->setResponse($response);
                return;
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $postFilterConfigs = $this->filterConfig['postFilters'];
        if (empty($postFilterConfigs)) {
            return;
        }

        $response = $event->getResponse();

        foreach ($postFilterConfigs as $name => $postFilterConfig) {
            $class = $postFilterConfig['class'];
            $filter = new $class();
            $filter->setResponse($response);
            $filter->doFilter();
        }
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 0)),
            KernelEvents::RESPONSE => array(array('onKernelResponse', 0)),
        );
    }

}
