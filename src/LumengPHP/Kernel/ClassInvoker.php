<?php

namespace LumengPHP\Kernel;

use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use ReflectionClass;

/**
 * 类调用者
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ClassInvoker {

    /**
     * 默认的入口方法名称
     */
    const ENTRY_METHOD = 'execute';

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var PropertyInjectorInterface 属性注入器
     */
    private $propertyInjector;

    /**
     * 构造一个<b>ClassInvoker</b>对象
     * @param AppContextInterface $appContext 应用环境实例
     * @param PropertyInjectorInterface $propertyInjector 属性注入器实例
     */
    public function __construct(AppContextInterface $appContext, PropertyInjectorInterface $propertyInjector) {
        $this->appContext = $appContext;
        $this->propertyInjector = $propertyInjector;
    }

    /**
     * 调用类并返回一个结果对象
     * @param string $class 要被调用的类的全限定名称
     * @return type
     */
    public function invoke($class) {
        $classObject = new $class();
        $reflectionObj = new ReflectionClass($class);

        //加载类元数据
        $metadataLoader = new ClassMetadataLoader($this->appContext, $reflectionObj);
        $classMetadata = $metadataLoader->load();

        //注入属性
        $propertyMetadata = $classMetadata['propertyMetadata'];
        $this->propertyInjector->inject($classObject, $reflectionObj, $propertyMetadata);

        //如果有init方法，先执行init方法
        if ($reflectionObj->hasMethod('init')) {
            $classObject->init();
        }

        //执行类入口方法并返回
        $method = self::ENTRY_METHOD;
        $return = $classObject->$method();
        return $return;
    }

}
