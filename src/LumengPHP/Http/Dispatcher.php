<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\AppConfig;
use LumengPHP\Http\Routing\RouterInterface;
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
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var RouterInterface 路由器实例
     */
    private $router;

    /**
     * @var string 当前请求的uri
     */
    private $uri;

    /**
     * @var array 
     */
    private $bags;

    /**
     * @var string 当前请求的控制器类的全限定名称
     */
    private $controllerClass;

    public function __construct(AppContextInterface $appContext, RouterInterface $router) {
        $this->appContext = $appContext;
        $this->router = $router;
    }

    public function setAppConfig(AppConfigInterface $appConfig) {
        $this->appConfig = $appConfig;
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function setBags($bags) {
        $this->bags = $bags;
    }

    public function doDispatcher(Request $request) {
        try {
            $this->controllerClass = $this->router->route($request);

            //调用拦截器
            $this->invokeInterceptor();

            $metaDataCacheDir = RUNTIME_PATH . 'service_metadata';
            if (!is_dir($metaDataCacheDir)) {
                mkdir($metaDataCacheDir, 0755);
            }

            $controllerInvoker = new ControllerInvoker($this->controllerClass, $metaDataCacheDir, $this->bags);
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
        $interceptors = $this->appConfig->getInterceptors();
        foreach ($interceptors as $interceptor) {
            $interceptorObj = new $interceptor();
            $interceptorObj->setUri($this->uri);
            $interceptorObj->setServiceClass($this->controllerClass);
            $interceptorObj->execute();
        }
    }

    private function outputJson($jsonString) {
        header('Content-Type:application/json; charset=utf-8');
        echo $jsonString;
    }

}
