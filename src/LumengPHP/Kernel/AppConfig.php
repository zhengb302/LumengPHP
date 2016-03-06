<?php

namespace LumengPHP\Kernel;

/**
 * 应用配置接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppConfig {

    /**
     * 返回应用配置数据
     * @param string $key 配置key
     * @return mixed
     */
    public function get($key);
}
