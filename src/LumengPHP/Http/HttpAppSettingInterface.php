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
     * 取得控制器父名称空间，尾部不能带反斜杠
     * @return string 
     */
    public function getControllerParentNamespace();
}
