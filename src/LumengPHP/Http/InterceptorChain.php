<?php

namespace LumengPHP\Http;

use Exception;
use LumengPHP\Kernel\ClassInvoker;

/**
 * 拦截器链
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class InterceptorChain implements InterceptorChainInterface {

    /**
     * @var array 拦截器列表
     */
    private $interceptors;

    /**
     * @var int 拦截器数量
     */
    private $interceptorCount;

    /**
     * @var int 当前位置，从0开始
     */
    private $pos;

    /**
     * @var string 控制器类全限定名称 
     */
    private $controllerClass;

    /**
     * @var ClassInvoker 类调用者
     */
    private $classInvoker;

    /**
     * @var Exception 
     */
    private $exception;

    public function __construct(array $interceptors, $controllerClass, ClassInvoker $classInvoker) {
        $this->interceptors = $interceptors;
        $this->interceptorCount = count($interceptors);
        $this->pos = 0;

        $this->controllerClass = $controllerClass;
        $this->classInvoker = $classInvoker;
    }

    /**
     * 是否调用拦截器或控制器过程中发生了异常
     * 
     * @return bool true表示发生了异常，false表示未发生异常
     */
    public function hasException() {
        return !is_null($this->exception);
    }

    /**
     * 返回发生的异常对象
     * 
     * @return Exception|null
     */
    public function getException() {
        return $this->exception;
    }

    public function invoke() {
        //拦截器已经调用完了
        if ($this->pos >= $this->interceptorCount) {
            $current = $this->controllerClass;
        }
        //拦截器尚未调用完
        else {
            $current = $this->interceptors[$this->pos++];
        }

        try {
            $return = $this->classInvoker->invoke($current);
        } catch (Exception $ex) {
            //只保留最开始抛出的那个异常
            if (!$this->exception) {
                $this->exception = $ex;
            }

            $return = null;
        }

        return $return;
    }

}
