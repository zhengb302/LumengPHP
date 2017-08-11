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
        $interceptors = $this->appContext->getConfig('interceptors');
        foreach ($interceptors as $interceptorClass) {
            if (!class_exists($interceptorClass)) {
                throw new Exception("拦截器{$interceptorClass}不存在~");
            }

            $this->classInvoker->invoke($interceptorClass);
        }
    }

}
