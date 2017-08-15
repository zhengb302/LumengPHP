<?php

namespace LumengPHP\Kernel;

/**
 * 配置加载器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface ConfigLoaderInterface {

    /**
     * 执行加载配置文件的动作
     * @param string $configFilePath 配置文件
     */
    public function load($configFilePath);
}
