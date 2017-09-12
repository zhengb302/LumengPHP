<?php

namespace LumengPHP\Console;

use Exception;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\PropertyInjectorInterface;
use ReflectionClass;

/**
 * 控制台属性注入器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ConsolePropertyInjector implements PropertyInjectorInterface {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var mixed 类对象
     */
    private $classObj;

    /**
     * @var ReflectionClass 
     */
    private $reflectionObj;

    /**
     * @var array 属性注解元数据，属性名称作为key 
     */
    private $metadatas;

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

    public function inject($classObj, ReflectionClass $reflectionObj, array $metadatas) {
        $this->classObj = $classObj;
        $this->reflectionObj = $reflectionObj;
        $this->metadatas = $metadatas;

        foreach ($this->metadatas as $propertyName => $metadata) {
            $this->doInject($propertyName, $metadata);
        }
    }

    private function doInject($propertyName, array $metadata) {
        $source = $metadata['source'];
        $paramName = isset($metadata['paramName']) ? $metadata['paramName'] : $propertyName;

        switch ($source) {
            case 'config':
                $rawValue = $this->appContext->getConfig($paramName);
                break;
            case 'service':
                $rawValue = $this->appContext->getService($paramName);
                break;
        }

        //源数据不存在时，且设置了“@keepDefault”注解时，保持属性的原值
        if (is_null($rawValue) && isset($metadata['keepDefault'])) {
            return;
        }

        if ($source == 'service') {
            $value = $rawValue;
        } else {
            $value = $this->formatValue($metadata['type'], $rawValue);
        }

        $property = $this->reflectionObj->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($this->classObj, $value);
    }

    private function formatValue($type, $rawValue) {
        switch ($type) {
            case 'int':
            case 'long':
                return (int) $rawValue;
            case 'float':
            case 'double':
                return (float) $rawValue;
            case 'bool':
                return $rawValue == '0' || $rawValue == '' || $rawValue == 'false' ? false : true;
            case 'string':
                return trim((string) $rawValue);
            case 'array':
                //对于数组类型，如果值不是数组，则按英文逗号分隔字符串再返回一个数组
                return is_array($rawValue) ? $rawValue : explode(',', $rawValue);
            default:
                throw new Exception("不支持的数据类型：{$type}");
        }
    }

}
