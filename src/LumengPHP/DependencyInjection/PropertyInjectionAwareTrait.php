<?php

namespace LumengPHP\DependencyInjection;

/**
 * 可注入属性 trait
 *
 * @author Lumeng <zhengb302@163.com>
 */
trait PropertyInjectionAwareTrait {

    /**
     * 设置属性值
     * @param string $name 属性名称
     * @param mixed $value 属性值
     * @return void
     */
    public function setProperty($name, $value) {
        $this->$name = $value;
    }

}
