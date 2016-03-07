<?php

namespace LumengPHP\Kernel;

/**
 * 应用环境接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppContext {

    /**
     * 返回AppConfig实例
     * @return AppConfig
     */
    public function getAppConfig();
}
