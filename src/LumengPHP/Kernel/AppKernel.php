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
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppKernel implements HttpKernelInterface, TerminableInterface {

    /**
     * @var array 
     */
    private $configs;

    /**
     * @var HttpKernel 
     */
    private $kernel;

    public function __construct($configFilepath) {
        $this->configs = require($configFilepath);
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
        //@todo 在AppKernel的构造器中，就应该使用AppConfig，而不是推迟到这里
        $appConfig = new AppConfig($this->configs);

        //@todo AppContext的实现类从配置中获取，而不是直接写死
        $appContext = new AppContextImpl($appConfig);

        $requestStack = new RequestStack();

        $routes = new RouteCollection();
        $routeConfigs = $this->configs['framework']['router'];
        foreach ($routeConfigs as $name => $routeConfig) {
            $path = $routeConfig['path'];
            $defaults = $routeConfig;
            $routes->add($name, new Route($path, $defaults));
        }

        $matcher = new UrlMatcher($routes, new RequestContext());

        $dispatcher = new EventDispatcher();

        $routerListener = new RouterListener($matcher, $requestStack);
        $dispatcher->addSubscriber($routerListener);

        $cmdInitListener = new CommandInitializationListener($appContext);
        $dispatcher->addSubscriber($cmdInitListener);

        $filterConfig = $this->configs['framework']['filter'];
        $filterListener = new FilterListener($filterConfig, $appContext);
        $dispatcher->addSubscriber($filterListener);

        $resolver = new ControllerResolver();
        $this->kernel = new HttpKernel($dispatcher, $resolver, $requestStack);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response) {
        return $this->kernel->terminate($request, $response);
    }

}
