<?php

namespace Djj\Core;

/**
 * 拦截器抽象基类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
abstract class AbstractInterceptor implements InterceptorInterface {

    protected $uri;
    protected $serviceClass;

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function setServiceClass($serviceClass) {
        $this->serviceClass = $serviceClass;
    }

}
