<?php

namespace LumengPHP\Http;

/**
 * 拦截器链接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface InterceptorChainInterface {

    /**
     * 调用下一个拦截器，如果拦截器已经调用完，则调用控制器
     * 
     * @return mixed
     */
    public function invoke();
}
