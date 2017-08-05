<?php

namespace LumengPHP\Kernel\Annotation;

use ArrayAccess;

/**
 * 注解元数据
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class MetaData implements ArrayAccess {

    /**
     * @var array 元数据key=>value对
     */
    private $metaData = [];

    /**
     * 添加元数据
     * @param string $name
     * @param mixed $value
     */
    public function addMetaData($name, $value) {
        $this->metaData[$name] = $value;
    }

    /**
     * 返回所有元数据
     * @return array 
     */
    public function getAllMetaData() {
        return $this->metaData;
    }

    /**
     * 是否含有某个元数据
     * @param string $name 元数据名称
     * @return bool
     */
    public function has($name) {
        return isset($this->metaData[$name]);
    }

    /**
     * 获取某个元数据的值
     * @param string $name 元数据名称
     * @return mixed
     */
    public function get($name) {
        return isset($this->metaData[$name]) ? $this->metaData[$name] : null;
    }

    public function offsetExists($offset) {
        return isset($this->metaData[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->metaData[$offset]) ? $this->metaData[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        $this->metaData[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->metaData[$offset]);
    }

}
