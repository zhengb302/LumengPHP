<?php

namespace LumengPHP\Http;

use Exception;
use LumengPHP\Http\Result\ResultHandlerInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\ClassInvoker;

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

    public function __construct(AppContextInterface $appContext, RouterInterface $router, ResultHandlerInterface $resultHandler) {
        $this->appContext = $appContext;
        $this->router = $router;
        $this->resultHandler = $resultHandler;
    }

    public function doDispatcher(Request $request) {
        $propertyInjector = new HttpPropertyInjector($this->appContext, $request);
        $classInvoker = new ClassInvoker($this->appContext, $propertyInjector);

        try {
            //路由
            $controllerClass = $this->router->route($request);
            $pathInfo = $this->router->getPathInfo();

            //捞出当前pathinfo匹配的拦截器列表
            /* @var $appSetting HttpAppSettingInterface */
            $appSetting = $this->appContext->getAppSetting();
            $interceptors = $appSetting->getInterceptors();
            $interceptorMatcher = new InterceptorMatcher($pathInfo, $interceptors);
            $matchedInterceptors = $interceptorMatcher->match();

            //创建拦截器链对象，并且把其注册为服务，然后再执行拦截器链
            $interceptorChain = new InterceptorChain($matchedInterceptors, $controllerClass, $classInvoker);
            $this->appContext->getServiceContainer()->register('interceptorChain', $interceptorChain);
            $return = $interceptorChain->invoke();
            if ($interceptorChain->hasException()) {
                throw $interceptorChain->getException();
            }

            //处理控制器返回
            $result = $this->resultHandler->handleReturn($return);
        } catch (Exception $ex) {
            //处理异常
            $result = $this->resultHandler->handleException($ex);
        }

        //处理最终的结果
        $this->resultHandler->handleResult($result);
    }

}
