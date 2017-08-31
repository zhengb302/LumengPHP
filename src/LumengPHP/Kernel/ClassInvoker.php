<?php

namespace LumengPHP\Kernel;

use ReflectionClass;
use LumengPHP\Kernel\Annotation\ClassAnnotationDumper;

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
     * @var mixed 要调用的对象
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
     * @param string $class 要调用的类的全限定名称
     * @return type
     */
    public function invoke($class) {
        $this->classObject = new $class();
        $this->reflectionObj = new ReflectionClass($class);

        //加载类元数据
        $this->loadClassMetadata();

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

    /**
     * 加载类元数据
     */
    private function loadClassMetadata() {
        $metadataCacheDir = $this->appContext->getRuntimeDir() . '/cache/class-metadata';
        if (!is_dir($metadataCacheDir)) {
            mkdir($metadataCacheDir, 0755, true);
        }

        //inode、最后修改时间
        $classFilePath = $this->reflectionObj->getFileName();
        $inode = fileinode($classFilePath);
        $lastModifiedTime = filemtime($classFilePath);

        //类的全限定名称
        $classFullName = $this->reflectionObj->getName();

        //类元数据缓存文件名及路径
        $cacheFileName = str_replace('\\', '.', $classFullName) . ".{$inode}.{$lastModifiedTime}.php";
        $cacheFilePath = $metadataCacheDir . '/' . $cacheFileName;

        if (is_file($cacheFilePath)) {
            $this->classMetadata = require($cacheFilePath);
        } else {
            $classAnnotationDumper = new ClassAnnotationDumper($this->reflectionObj);
            $this->classMetadata = $classAnnotationDumper->dump($cacheFilePath);
        }
    }

}
