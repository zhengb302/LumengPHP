<?php

namespace LumengPHP\Kernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use LumengPHP\Kernel\Response;
use LumengPHP\Kernel\AppContext;

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

    /**
     * @var AppContext 
     */
    private $appContext;

    public function __construct(array $filterConfig, AppContext $appContext) {
        $this->filterConfig = $filterConfig;
        $this->appContext = $appContext;
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

        $currentRouteName = $request->attributes->get('_route');

        foreach ($preFilterConfigs as $name => $preFilterConfig) {
            if (!empty($preFilterConfig['routes']) &&
                    !in_array($currentRouteName, $preFilterConfig['routes'])) {
                continue;
            }

            $class = $preFilterConfig['class'];
            $filter = new $class();
            $filter->setAppContext($this->appContext);
            $filter->setRequest($request);
            $filter->init();

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

        $request = $event->getRequest();
        $response = $event->getResponse();

        $currentRouteName = $request->attributes->get('_route');

        foreach ($postFilterConfigs as $name => $postFilterConfig) {
            if (!empty($postFilterConfig['routes']) &&
                    !in_array($currentRouteName, $postFilterConfig['routes'])) {
                continue;
            }

            $class = $postFilterConfig['class'];
            $filter = new $class();
            $filter->setAppContext($this->appContext);
            $filter->setRequest($request);
            $filter->setResponse($response);
            $filter->init();

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
