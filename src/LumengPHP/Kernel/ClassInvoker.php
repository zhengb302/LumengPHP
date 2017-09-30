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
     * @var string 入口方法名称
     */
    private $entryMethod = 'execute';

    /**
     * @var object 要调用的对象
     */
    private $classObject;

    /**
     * @var ReflectionClass 
     */
    private $reflectionObj;

    /**
     * @var array 类注解元数据
     */
    private $classMetadata;

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
        $this->classObject = new $class();
        $this->reflectionObj = new ReflectionClass($class);

        //加载类元数据
        $metadataLoader = new ClassMetadataLoader($this->appContext, $this->reflectionObj);
        $this->classMetadata = $metadataLoader->load();

        //注入属性
        $propertyMetadata = $this->classMetadata['propertyMetadata'];
        $this->propertyInjector->inject($this->classObject, $this->reflectionObj, $propertyMetadata);

        //如果有init方法，先执行init方法
        if ($this->reflectionObj->hasMethod('init')) {
            $this->classObject->init();
        }

        //执行类入口方法并返回
        $method = $this->entryMethod;
        $return = $this->classObject->$method();
        return $return;
    }

}
