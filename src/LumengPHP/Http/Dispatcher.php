<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Http\Result\ResultHandlerInterface;
use LumengPHP\Kernel\ClassInvoker;
use Exception;
use LumengPHP\Http\Result\Result;
use LumengPHP\Http\Result\Success;
use LumengPHP\Http\Result\Failed;

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
            $result = $this->convertReturnToResult($return);
        } catch (Exception $ex) {
            //@todo 实现开发者可配置的异常处理器，以实现更精细的异常控制。
            $result = new Failed($ex->getMessage());
            if ($ex->getCode() < 0) {
                $result->setStatus($ex->getCode());
            }
        }

        $this->resultHandler->handle($result);
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

    /**
     * 转换控制器方法返回结果为Result对象
     * @param mixed $return
     * @return Result
     */
    private function convertReturnToResult($return) {
        //控制器方法可以直接返回一个Result对象
        if ($return instanceof Result) {
            $result = $return;
        }
        //啥都没返回，或者返回null
        elseif (is_null($return)) {
            $result = new Success();
        }
        //或者直接返回一个数据，这是执行成功的一种表现
        else {
            $result = new Success('', $return);
        }

        return $result;
    }

}
