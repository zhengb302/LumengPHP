<?php

namespace LumengPHP\Routing;

/**
 * 路由器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Router {

    /**
     * @var array 规则map：path => configs
     */
    private $rules;

    /**
     * 增加一条路由规则
     * @param string $path
     * @param array $configs
     */
    public function addRule($path, $configs) {
        $this->rules[$path] = $configs;
    }

    /**
     * 匹配一个路由
     * @param string $path 请求路径
     * @return array|null 匹配成功，返回这个路由的配置；匹配失败，返回null
     */
    public function match($path) {
        return isset($this->rules[$path]) ? $this->rules[$path] : null;
    }

}
