<?php

namespace LumengPHP\Kernel;

use ReflectionClass;

/**
 * 属性注入器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface PropertyInjectorInterface {

    /**
     * 执行注入动作
     * @param mixed $classObj 要被注入的类对象
     * @param ReflectionClass $reflectionObj 要被注入的类对象的反射对象
     * @param array $metadatas 属性元数据
     */
    public function inject($classObj, ReflectionClass $reflectionObj, array $metadatas);
}
