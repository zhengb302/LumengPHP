<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Http\Result\ResultHandlerInterface;
use LumengPHP\Kernel\ClassInvoker;
use Exception;

/**
 * HTTP请求派发器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Dispatcher {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var RouterInterface 路由器实例
     */
    private $router;

    /**
     * @var ResultHandlerInterface 结果处理器
     */
    private $resultHandler;

    /**
     * @var ClassInvoker 类调用者
     */
    private $classInvoker;

    public function __construct(AppContextInterface $appContext, RouterInterface $router, ResultHandlerInterface $resultHandler) {
        $this->appContext = $appContext;
        $this->router = $router;
        $this->resultHandler = $resultHandler;
    }

    public function doDispatcher(Request $request) {
        $propertyInjector = new HttpPropertyInjector($this->appContext, $request);
        $this->classInvoker = new ClassInvoker($this->appContext, $propertyInjector);

        try {
            //调用拦截器
            $this->invokeInterceptor();

            //路由
            $controllerClass = $this->router->route($request);

            //调用控制器
            $return = $this->classInvoker->invoke($controllerClass);

            //处理控制器返回
            $result = $this->resultHandler->handleReturn($return);
        } catch (Exception $ex) {
            //处理异常
            $result = $this->resultHandler->handleException($ex);
        }

        //处理最终的结果
        $this->resultHandler->handleResult($result);
    }

    /**
     * 调用拦截器
     * @throws Exception
     */
    private function invokeInterceptor() {
        /* @var $appSetting HttpAppSettingInterface */
        $appSetting = $this->appContext->getAppSetting();
        $interceptors = $appSetting->getInterceptors();
        foreach ($interceptors as $interceptorClass) {
            if (!class_exists($interceptorClass)) {
                throw new Exception("拦截器{$interceptorClass}不存在~");
            }

            $this->classInvoker->invoke($interceptorClass);
        }
    }

    /**
     * // 拦截器配置
      [
        Profiler::class => '*',
        LogFlusher::class => '*',
        UserAuth::class => '*, ~/user/login, ~/order/submit',
      ];

      //路由配置
      [
        '/helloWorld' => \Bear\BBS\Controllers\HelloWorld::class,
        '/user/greetUser' => \Bear\BBS\Controllers\User\GreetUser::class,
        '/user/showUser' => \Bear\BBS\Controllers\User\ShowUser::class,
      ]
     */
    private function buildInterceptorCache() {
        /* @var $appSetting HttpAppSettingInterface */
        $appSetting = $this->appContext->getAppSetting();

        $interceptors = $appSetting->getInterceptors();

        $routingConfig = $appSetting->getRoutingConfig();
        $uriPaths = array_keys($routingConfig);
        foreach ($uriPaths as $uriPath) {
            foreach ($interceptors as $interceptor => $patternVal) {
                
            }
        }
    }

    private function parsePattern($patternVal) {
        $patterns = [];
        $excludePatterns = [];
        foreach (explode(',', $patternVal) as $rawPattern) {
            $pattern = trim($rawPattern);
            if (!$pattern) {
                continue;
            }

            if ($pattern[0] == '~') {
                $excludePatterns[] = substr($pattern, 1);
            } else {
                $patterns[] = $pattern;
            }
        }

        return [$patterns, $excludePatterns];
    }

}
