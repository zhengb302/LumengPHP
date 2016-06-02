<?php

namespace LumengPHP\Kernel;

/**
 * 应用配置类<br />
 * 接受已加载好的配置数据(关联数组)，根据key查询配置数据。
 * 此类只负责查询数据，怎么加载配置，则由其他组件负责。
 * 通常配置数据的格式变化比较小，而加载配置数据的方式可能多种多样，
 * 所以未来可能定义一个叫"ConfigLoader"的组件专门用于加载配置数据。
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class AppConfig {

    /**
     * @var array 配置数据，关联数组
     */
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * 返回应用配置数据。支持多维搜索。<br />
     * 所谓的"多维搜索"，其实就是使用像"foo.bar"这样的key来搜索
     * 如果没找到则返回<b>null</b>
     * 
     * @param string $key 配置key
     * @return mixed|null
     */
    public function get($key) {
        if (strpos($key, '.') !== false) {
            return $this->deepSearch($key);
        }

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * 深度搜索key
     * 
     * @param string $key 带英文句号"."的key
     * @return mixed|null
     */
    private function deepSearch($key) {
        $last = $this->config;

        $keyComponents = explode('.', $key);
        foreach ($keyComponents as $component) {
            if (!isset($last[$component])) {
                return null;
            }

            $last = $last[$component];
        }

        return $last;
    }

}
