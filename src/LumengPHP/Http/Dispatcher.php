<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppConfig;
use Exception;
use Djj\Result\Failed;

/**
 * HTTP请求派发器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Dispatcher {

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var string 当前请求的uri
     */
    private $uri;

    /**
     * @var array 
     */
    private $bags;

    /**
     * @var string 当前请求的服务类的全限定名称
     */
    private $serviceClass;

    public function setAppConfig(AppConfigInterface $appConfig) {
        $this->appConfig = $appConfig;
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function setBags($bags) {
        $this->bags = $bags;
    }

    public function doDispatcher($controllerName, $actionName) {
        try {
            $this->verifyServiceComponentName($controllerName);
            $this->verifyServiceComponentName($actionName);

            $this->serviceClass = "Service\\{$controllerName}\\{$actionName}";
            if (!class_exists($this->serviceClass)) {
                throw new Exception('您请求的服务不存在~');
            }

            //调用拦截器
            $this->invokeInterceptor();

            $metaDataCacheDir = RUNTIME_PATH . 'service_metadata';
            if (!is_dir($metaDataCacheDir)) {
                mkdir($metaDataCacheDir, 0755);
            }

            $serviceInvoker = new ServiceInvoker($this->serviceClass, $metaDataCacheDir, $this->bags);
            $result = $serviceInvoker->invoke();
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

    private function verifyServiceComponentName($componentName) {
        //大写英文字母开头，后边跟着一个或多个英文字母
        if (!preg_match('/^[A-Z][A-Za-z]+$/', $componentName)) {
            throw new Exception('服务名称非法→_→');
        }
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
            $interceptorObj->setServiceClass($this->serviceClass);
            $interceptorObj->execute();
        }
    }

    private function outputJson($jsonString) {
        header('Content-Type:application/json; charset=utf-8');
        echo $jsonString;
    }

}
