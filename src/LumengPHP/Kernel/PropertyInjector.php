<?php

namespace LumengPHP\Kernel;

use ReflectionClass;
use Exception;

/**
 * 属性注射器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class PropertyInjector {

    /**
     * @var mixed 服务对象
     */
    private $serviceObj;

    /**
     * @var ReflectionClass 
     */
    private $reflectionObj;

    /**
     * @var array 属性注解元数据，属性名称作为key 
     */
    private $metaDatas;

    /**
     * @var array 
     */
    private $get;

    /**
     * @var array 
     */
    private $post;

    /**
     * @var array 
     */
    private $request;

    /**
     * @var SessionInterface 
     */
    private $session;

    public function __construct($serviceObj, $reflectionObj, $metaDatas, $bags) {
        $this->serviceObj = $serviceObj;
        $this->reflectionObj = $reflectionObj;
        $this->metaDatas = $metaDatas;

        $this->get = $bags['get'];
        $this->post = $bags['post'];
        $this->request = $bags['request'];
        $this->session = $bags['session'];
    }

    public function inject() {
        foreach ($this->metaDatas as $propertyName => $metaData) {
            $this->doInject($propertyName, $metaData);
        }
    }

    private function doInject($propertyName, array $metaData) {
        $source = $metaData['source'];
        $paramName = isset($metaData['paramName']) ? $metaData['paramName'] : $propertyName;

        switch ($source) {
            case 'get':
                $rawValue = $this->get[$paramName];
                break;
            case 'post':
                $rawValue = $this->post[$paramName];
                break;
            case 'request':
                $rawValue = $this->request[$paramName];
                break;
            case 'session':
                $rawValue = $this->session[$paramName];
                break;
        }
        $value = $this->formatValue($metaData['type'], $rawValue);

        $property = $this->reflectionObj->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($this->serviceObj, $value);
    }

    private function formatValue($type, $value) {
        switch ($type) {
            case 'int':
            case 'long':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'bool':
                return $value == '0' || $value == '' || $value == 'false' ? false : true;
            case 'string':
                return (string) $value;
            case 'array':
                //对于数组类型，如果值不是数组，则按英文逗号分隔字符串再返回一个数组
                return is_array($value) ? $value : explode(',', $value);
            default:
                throw new Exception("不支持的数据类型：{$type}");
        }
    }

}
