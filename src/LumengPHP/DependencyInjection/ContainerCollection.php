<?php

namespace LumengPHP\DependencyInjection;

/**
 * 容器集合
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ContainerCollection {

    /**
     * @var array 名称-容器对象map
     */
    private $containers;

    /**
     * 向容器集合中添加容器
     * @param string $name
     * @param ContainerInterface $container
     */
    public function add($name, ContainerInterface $container) {
        $this->containers[$name] = $container;
    }

    /**
     * 返回名称为$name的容器
     * @param string $name
     * @return ContainerInterface|null 存在则返回容器对象，不存在则返回null
     */
    public function get($name) {
        return isset($this->containers[$name]) ? $this->containers[$name] : null;
    }

}
