<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use LumengPHP\Kernel\ControllerResolver;
use LumengPHP\Kernel\EventListener\CommandInitializationListener;
use LumengPHP\Kernel\EventListener\FilterListener;

/**
 * AppKernel convert a Request object to a Response one.
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppKernel implements HttpKernelInterface, TerminableInterface {

    /**
     * @var AppConfig AppConfig对象
     */
    private $appConfig;

    /**
     * @var HttpKernel 
     */
    private $kernel;

    public function __construct($configFilepath) {
        $this->appConfig = new AppConfig(require($configFilepath));
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) {
        //初始化
        $this->initialize();

        //处理请求
        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * 初始化
     */
    private function initialize() {
        $appContext = new AppContextImpl($this->appConfig);

        $routes = $this->loadRoutes();
        $matcher = new UrlMatcher($routes, new RequestContext());

        $requestStack = new RequestStack();

        $dispatcher = new EventDispatcher();

        $routerListener = new RouterListener($matcher, $requestStack);
        $dispatcher->addSubscriber($routerListener);

        $cmdInitListener = new CommandInitializationListener($appContext);
        $dispatcher->addSubscriber($cmdInitListener);

        $filterConfig = $this->appConfig->get('framework.filter');
        $filterListener = new FilterListener($filterConfig, $appContext);
        $dispatcher->addSubscriber($filterListener);

        $resolver = new ControllerResolver();
        $this->kernel = new HttpKernel($dispatcher, $resolver, $requestStack);
    }

    /**
     * 加载路由
     * @return RouteCollection
     */
    private function loadRoutes() {
        $routes = new RouteCollection();
        $routeConfigs = $this->appConfig->get('framework.router');
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

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response) {
        return $this->kernel->terminate($request, $response);
    }

}
