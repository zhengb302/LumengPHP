<?php

namespace LumengPHP\Http;

use ReflectionClass;
use Djj\Result\Result;
use Djj\Result\Success;
use LumengPHP\Kernel\Annotation\ClassAnnotationDumper;

/**
 * 控制器调用者
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ControllerInvoker {

    /**
     * @var string 入口方法名称
     */
    private $entryMethod = 'execute';

    /**
     * @var mixed 服务对象
     */
    private $serviceObject;

    /**
     * @var ReflectionClass 
     */
    private $reflectionObj;

    /**
     * @var array 类注解元数据
     */
    private $classAnnotation;

    /**
     * @var array 
     */
    private $bags;

    /**
     * @var bool 是否响应内容下划线转驼峰处理
     */
    private $camelCase = false;

    public function __construct($serviceClass, $cacheDir, $bags) {
        $this->serviceObject = new $serviceClass();
        $this->reflectionObj = new ReflectionClass($serviceClass);

        $serviceFilePath = $this->reflectionObj->getFileName();
        $serviceLastModifiedTime = filemtime($serviceFilePath);
        $cacheFilePath = $cacheDir . '/' . strtolower(str_replace('\\', '_', $this->reflectionObj->getName())) . "_{$serviceLastModifiedTime}.php";
        if (is_file($cacheFilePath)) {
            $this->classAnnotation = require($cacheFilePath);
        } else {
            $classAnnotationDumper = new ClassAnnotationDumper($this->reflectionObj);
            $this->classAnnotation = $classAnnotationDumper->dump($cacheFilePath);
        }

        $this->bags = $bags;
    }

    /**
     * 调用服务并返回一个结果对象
     * @return Result
     */
    public function invoke() {
        //属性注入
        $propertyAnnotationMetaData = $this->classAnnotation['propertyAnnotationMetaData'];
        $propertyInjector = new PropertyInjector($this->serviceObject, $this->reflectionObj, $propertyAnnotationMetaData, $this->bags);
        $propertyInjector->inject();

        //如果有init方法，先执行init方法
        if ($this->reflectionObj->hasMethod('init')) {
            $this->serviceObject->init();
        }

        //执行服务入口方法
        $method = $this->entryMethod;
        $return = $this->serviceObject->$method();
        $result = $this->convertReturnToResult($return);

        //计算是否响应内容转驼峰，如果要下划线转驼峰，则转之
        $this->calcCamelCase();
        if ($this->camelCase) {
            $result->setData(camel_case($result->getData()));
        }

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

    /**
     * 计算是否响应内容转驼峰
     */
    private function calcCamelCase() {
        $methodAnnotationMetaData = $this->classAnnotation['methodAnnotationMetaData'];
        if (!isset($methodAnnotationMetaData[$this->entryMethod])) {
            return;
        }

        $metaData = $methodAnnotationMetaData[$this->entryMethod];
        if (isset($metaData['camelCase'])) {
            $this->camelCase = true;
        }
    }

}
