<?php

namespace LumengPHP\Kernel;

/**
 * 配置加载器实现
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ConfigLoader implements ConfigLoaderInterface {

    /**
     * @var array APP配置
     */
    private $config;

    public function load($configFilePath) {
        //APP配置
        $this->config = require($configFilePath);

        //环境配置
        $configDir = dirname($configFilePath);
        $envConfigPath = $configDir . '/env.php';
        $envConfig = [];
        if (is_file($envConfigPath)) {
            $envConfig = require($envConfigPath);
        }
    }

}
