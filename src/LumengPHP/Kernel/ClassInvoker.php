<?php

namespace LumengPHP\Kernel;

use ReflectionClass;
use Djj\Result\Result;
use Djj\Result\Success;
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
     * @param string $class 要调用的类的全限定名称
     * @param AppContextInterface $appContext 应用环境实例
     * @param PropertyInjectorInterface $propertyInjector 属性注入器实例
     */
    public function __construct($class, AppContextInterface $appContext, PropertyInjectorInterface $propertyInjector) {
        $this->classObject = new $class();
        $this->reflectionObj = new ReflectionClass($class);

        $this->appContext = $appContext;

        $this->propertyInjector = $propertyInjector;

        $this->init();
    }

    private function init() {
        $metaDataCacheDir = RUNTIME_PATH . 'class-metadata';
        if (!is_dir($metaDataCacheDir)) {
            mkdir($metaDataCacheDir, 0755);
        }

        $classFilePath = $this->reflectionObj->getFileName();
        $classLastModifiedTime = filemtime($classFilePath);
        $cacheFilePath = $metaDataCacheDir . '/' . strtolower(str_replace('\\', '_', $this->reflectionObj->getName())) . "_{$classLastModifiedTime}.php";
        if (is_file($cacheFilePath)) {
            $this->classMetadata = require($cacheFilePath);
        } else {
            $classAnnotationDumper = new ClassAnnotationDumper($this->reflectionObj);
            $this->classMetadata = $classAnnotationDumper->dump($cacheFilePath);
        }
    }

    /**
     * 调用服务并返回一个结果对象
     * @return Result
     */
    public function invoke() {
        //属性注入
        $propertyMetadata = $this->classMetadata['propertyAnnotationMetaData'];
        $this->propertyInjector->inject($this->classObject, $this->reflectionObj, $propertyMetadata);

        //如果有init方法，先执行init方法
        if ($this->reflectionObj->hasMethod('init')) {
            $this->classObject->init();
        }

        //执行服务入口方法
        $method = $this->entryMethod;
        $return = $this->classObject->$method();
        $result = $this->convertReturnToResult($return);

        return $result;
    }

    /**
     * 转换服务方法返回结果为Result对象
     * @param mixed $return
     * @return Result
     */
    private function convertReturnToResult($return) {
        //服务方法可以直接返回一个Result对象
        if ($return instanceof Result) {
            $result = $return;
        }
        //啥都没返回，或者返回null
        elseif (is_null($return)) {
            $result = new Success();
        }
        //或者直接返回一个数据，这是执行成功的一种表现
        else {
            $result = new Success('', $return);
        }

        return $result;
    }

}
