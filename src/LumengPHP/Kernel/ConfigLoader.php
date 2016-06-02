<?php

namespace LumengPHP\Kernel;

/**
 * 配置加载器<br />
 * 特性：
 *   支持配置继承
 *   支持配置缓存
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ConfigLoader {

    /**
     * 加载并返回配置数据
     * @param string $configFilePath 配置文件路径
     * @return array
     */
    public function load($configFilePath) {
        return $this->parse(require($configFilePath));
    }

    private function parse(array $config) {
        if (!isset($config['extends'])) {
            return $config;
        }

        $rawBaseConfig = require($config['extends']);
        $baseConfig = $this->parse($rawBaseConfig);

        return $this->mergeRecursive($baseConfig, $config);
    }

    private function mergeRecursive($baseConfig, $derivedConfig) {
        if (!is_array($baseConfig) || !is_array($derivedConfig)) {
            return $derivedConfig;
        }

        //如果两个都是下标数组，则合并再去重
        if ($this->isArrayIndexed($baseConfig) &&
                $this->isArrayIndexed($derivedConfig)) {

            //合并再去重
            $arr = array_unique(array_merge($baseConfig, $derivedConfig));

            //Re-index numeric array keys
            return array_values($arr);
        }

        foreach ($derivedConfig AS $key => $value) {
            $baseConfig[$key] = $this->mergeRecursive($baseConfig[$key], $value);
        }

        return $baseConfig;
    }

    /**
     * 是否数组是下标数组
     * @param array $array
     * @return boolean
     */
    private function isArrayIndexed($array) {
        return array_keys($array) === range(0, count($array) - 1);
    }

}
