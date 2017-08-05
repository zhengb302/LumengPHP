<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use LumengPHP\Kernel\EventListener\FilterListener;
use LumengPHP\Kernel\ControllerResolver;

/**
 * HTTP扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HttpExtension extends AbstractExtension {

    public function getName() {
        return 'LumengPHP-Http';
    }

    public function load() {
        $routes = $this->loadRoutes();
        $matcher = new UrlMatcher($routes, new RequestContext());

        $requestStack = new RequestStack();

        $dispatcher = new EventDispatcher();

        $routerListener = new RouterListener($matcher, $requestStack);
        $dispatcher->addSubscriber($routerListener);

        $filterConfig = $this->appContext->getConfig('httpKernel.filter');
        $filterListener = new FilterListener($filterConfig, $this->appContext);
        $dispatcher->addSubscriber($filterListener);

        $resolver = new ControllerResolver($this->appContext);
        $httpKernel = new HttpKernel($dispatcher, $resolver, $requestStack);

        //把HttpKernel对象注册为服务
        $this->container->registerService('http', $httpKernel);
    }

    /**
     * 加载路由
     * @return RouteCollection
     */
    private function loadRoutes() {
        $routes = new RouteCollection();
        $routeConfigs = $this->appContext->getConfig('httpKernel.router');
        foreach ($routeConfigs as $name => $routeConfig) {
            $path = $routeConfig['path'];
            $defaults = $routeConfig;

            $defaults['_path'] = $defaults['path'];
            $defaults['_cmd'] = $defaults['cmd'];
            unset($defaults['path'], $defaults['cmd']);

            $routes->add($name, new Route($path, $defaults));
        }

        return $routes;
    }

}
