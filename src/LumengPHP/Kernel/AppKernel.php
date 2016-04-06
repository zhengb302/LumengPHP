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
use LumengPHP\Kernel\EventListener\FilterListener;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\Extension;
use LumengPHP\Kernel\Facade\Facade;

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
     * @var ServiceContainer 服务容器
     */
    private $container;

    /**
     * @var AppContext 
     */
    private $appContext;

    /**
     * @var HttpKernel 
     */
    private $kernel;

    public function __construct($configFilePath) {
        $this->appConfig = new AppConfig(require($configFilePath));

        $this->initServiceContainer();

        $this->appContext = new AppContextImpl($this->appConfig, $this->container);

        $this->container->registerService('appContext', $this->appContext);

        Facade::setAppContext($this->appContext);

        //加载扩展
        $this->loadExtensions();
    }

    /**
     * 初始化服务容器
     */
    private function initServiceContainer() {
        $serviceConfigs = $this->appConfig->get('framework.services');

        //服务配置要不不存在，要不就是个数组
        assert(is_array($serviceConfigs) || is_null($serviceConfigs));

        if (is_null($serviceConfigs)) {
            $serviceConfigs = array();
        }

        $this->container = new ServiceContainer($serviceConfigs);
    }

    /**
     * 加载扩展
     */
    private function loadExtensions() {
        $extensions = $this->appConfig->get('framework.extensions');

        //扩展配置要不不存在，要不就是个数组
        assert(is_array($extensions) || is_null($extensions));

        if (empty($extensions)) {
            return;
        }

        foreach ($extensions as $extensionClass) {
            $extension = new $extensionClass();

            assert($extension instanceof Extension);

            $extension->load($this->appContext, $this->container);
        }
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
        $routes = $this->loadRoutes();
        $matcher = new UrlMatcher($routes, new RequestContext());

        $requestStack = new RequestStack();

        $dispatcher = new EventDispatcher();

        $routerListener = new RouterListener($matcher, $requestStack);
        $dispatcher->addSubscriber($routerListener);

        $filterConfig = $this->appConfig->get('framework.filter');
        $filterListener = new FilterListener($filterConfig, $this->appContext);
        $dispatcher->addSubscriber($filterListener);

        $resolver = new ControllerResolver($this->appContext);
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
