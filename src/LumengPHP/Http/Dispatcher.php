<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Kernel\ClassInvoker;
use Exception;
use Djj\Result\Result;
use Djj\Result\Success;
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

    /**
     * 转换服务方法返回结果为Result对象
     * @param mixed $return
     * @return Result
     */
    private function convertReturnToResult($return) {
        //服务方法可以直接返回一个Result对象
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

    private function outputJson($jsonString) {
        header('Content-Type:application/json; charset=utf-8');
        echo $jsonString;
    }

}
