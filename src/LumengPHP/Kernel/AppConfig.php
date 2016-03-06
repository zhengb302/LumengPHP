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
    private $configs;

    public function __construct(array $configs) {
        $this->configs = $configs;
    }

    /**
     * 返回应用配置数据
     * @param string $key 配置key
     * @return mixed
     */
    public function get($key) {
        return isset($this->configs[$key]) ? $this->configs[$key] : null;
    }

}
