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
     * @var array 
     */
    private $bags;

    public function __construct(AppContextInterface $appContext, RouterInterface $router) {
        $this->appContext = $appContext;
        $this->router = $router;
    }

    public function setBags($bags) {
        $this->bags = $bags;
    }

    public function doDispatcher(Request $request) {
        try {
            //调用拦截器
            $this->invokeInterceptor();

            $controllerClass = $this->router->route($request);
            $controllerInvoker = new ClassInvoker($controllerClass, $this->appContext, $this->bags);
            $result = $controllerInvoker->invoke();
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
        foreach ($interceptors as $interceptor) {
            $interceptorObj = new $interceptor();
        }
    }

    private function outputJson($jsonString) {
        header('Content-Type:application/json; charset=utf-8');
        echo $jsonString;
    }

}
