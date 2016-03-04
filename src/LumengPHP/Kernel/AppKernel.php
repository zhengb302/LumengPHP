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
        $this->initialize();
        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * 初始化
     */
    private function initialize() {
        $routes = new RouteCollection();
        $routeConfigs = $this->configs['framework']['router'];
        foreach ($routeConfigs as $name => $routeConfig) {
            $path = $routeConfig['path'];
            $defaults = $routeConfig;
            $routes->add($name, new Route($path, $defaults));
        }

        $matcher = new UrlMatcher($routes, new RequestContext());

        $dispatcher = new EventDispatcher();
        $routerListener = new RouterListener($matcher, new RequestStack());
        $dispatcher->addSubscriber($routerListener);

        $resolver = new ControllerResolver();
        $this->kernel = new HttpKernel($dispatcher, $resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response) {
        return $this->kernel->terminate($request, $response);
    }

}
