<?php

namespace LumengPHP\Kernel;

/**
 * 配置程序接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface Configuration {

    /**
     * 取得配置数据
     * @param string $key 配置key
     * @return mixed
     */
    public function get($key);
}
