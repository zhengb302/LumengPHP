<?php

namespace LumengPHP\Kernel;

use Exception;
use LumengPHP\Kernel\PropertyInjectorInterface;
use ReflectionClass;

/**
 * 属性注入器抽象基类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
abstract class AbstractPropertyInjector implements PropertyInjectorInterface {

    /**
     * @var mixed 类对象
     */
    protected $classObj;

    /**
     * @var ReflectionClass 
     */
    protected $reflectionObj;

    /**
     * @var array 属性注解元数据，属性名称作为key 
     */
    protected $metadatas;

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

        $rawValue = $this->getRawValue($source, $paramName);

        //源数据不存在时，且设置了“@keepDefault”注解时，保持属性的原值
        if (is_null($rawValue) && isset($metadata['keepDefault'])) {
            return;
        }

        if (in_array($source, ['service', 'currentEvent'])) {
            $value = $rawValue;
        } else {
            $value = $this->convertValue($metadata['type'], $rawValue);
        }

        $property = $this->reflectionObj->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($this->classObj, $value);
    }

    /**
     * 根据数据源名称及参数名称取得原始值
     * 
     * @param string $source 数据源名称
     * @param string $paramName 参数名称
     * @return mixed|null 原始值。如果不存在，则返回<b>null</b>
     */
    abstract protected function getRawValue($source, $paramName);

    /**
     * 转换原始值的数据类型
     * 
     * @param string $type 目标数据类型
     * @param mixed $rawValue 原始值
     * @return mixed 目前数据类型的值
     * @throws Exception
     */
    private function convertValue($type, $rawValue) {
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
