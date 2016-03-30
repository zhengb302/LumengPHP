<?php

namespace LumengPHP\DependencyInjection;

/**
 * 可注入属性 接口
 * @author Lumeng <zhengb302@163.com>
 */
interface PropertyInjectionAwareInterface {

    /**
     * 设置属性值
     * @param string $name 属性名称
     * @param mixed $value 属性值
     * @return void
     */
    public function setProperty($name, $value);
}
