<?php

namespace Djj\Core;

/**
 * 拦截器接口
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface InterceptorInterface {

    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setUri($uri);

    /**
     * 设置当前请求的服务类的全限定名称
     * @param string $serviceClass
     */
    public function setServiceClass($serviceClass);

    /**
     * 拦截器入口方法
     */
    public function execute();
}
