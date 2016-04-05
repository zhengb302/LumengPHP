<?php

namespace tests\Misc;

use LumengPHP\DependencyInjection\ContainerInterface;

/**
 * 模拟容器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class MockContainer implements ContainerInterface {

    /**
     *
     * @var array 格式：name => object
     */
    private $objects;

    public function __construct(array $objects) {
        $this->objects = $objects;
    }

    public function has($key) {
        return isset($this->objects[$key]);
    }

    public function get($key) {
        return isset($this->objects[$key]) ? $this->objects[$key] : null;
    }

}
