<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppSettingInterface;

/**
 * HTTP APP设置接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface HttpAppSettingInterface extends AppSettingInterface {

    /**
     * 取得拦截器配置
     * @return array
     */
    public function getInterceptors();

    /**
     * 取得路由配置<br />
     * 应用根据使用的路由实现，返回相应的路由配置：可以是一个数组，也可以是一个字符串、对象实例，
     * 或者如果不需要路由配置，直接返回null
     * @return mixed 
     */
    public function getRoutingConfig();
}
