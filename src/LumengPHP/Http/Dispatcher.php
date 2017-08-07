<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Kernel\ClassInvoker;
use Exception;
use Djj\Result\Failed;

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
     * @var ClassInvoker 类调用者
     */
    private $classInvoker;

    public function __construct(AppContextInterface $appContext, RouterInterface $router) {
        $this->appContext = $appContext;
        $this->router = $router;
    }

    public function doDispatcher(Request $request) {
        $propertyInjector = new HttpPropertyInjector($request);
        $this->classInvoker = new ClassInvoker($this->appContext, $propertyInjector);

        try {
            //调用拦截器
            $this->invokeInterceptor();

            //路由
            $controllerClass = $this->router->route($request);

            //调用控制器
            $result = $this->classInvoker->invoke($controllerClass);
        } catch (Exception $ex) {
            //@todo 实现开发者可配置的异常处理器，以实现更精细的异常控制。
            $result = new Failed($ex->getMessage());
        }

        //狗日的APP客户端...
        $fuckingAppResultHead = [
            'code' => $result->getStatus(),
            'message' => $result->getMsg(),
        ];
        $fuckingAppResult = array_merge($fuckingAppResultHead, (array) $result->getData());

        $this->outputJson(json_encode($fuckingAppResult));
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

    private function outputJson($jsonString) {
        header('Content-Type:application/json; charset=utf-8');
        echo $jsonString;
    }

}
