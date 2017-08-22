<?php

namespace LumengPHP\Http;

use Exception;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\PropertyInjectorInterface;
use ReflectionClass;

/**
 * HTTP属性注入器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpPropertyInjector implements PropertyInjectorInterface {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var Request 
     */
    private $requestObj;

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

    public function __construct(AppContextInterface $appContext, Request $request) {
        $this->appContext = $appContext;

        $this->requestObj = $request;
        $this->get = $this->requestObj->get;
        $this->post = $this->requestObj->post;
        $this->request = $this->requestObj->request;
        $this->session = $this->requestObj->getSession();
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
            case 'config':
                $rawValue = $this->appContext->getConfig($paramName);
                break;
            case 'service':
                $rawValue = $this->appContext->getService($paramName);
                break;
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
                return trim((string) $value);
            case 'array':
                //对于数组类型，如果值不是数组，则按英文逗号分隔字符串再返回一个数组
                return is_array($value) ? $value : explode(',', $value);
            default:
                throw new Exception("不支持的数据类型：{$type}");
        }
    }

}
