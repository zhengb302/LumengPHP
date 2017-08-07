<?php

namespace LumengPHP\Kernel\Annotation;

use ArrayAccess;

/**
 * 注解元数据
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Metadata implements ArrayAccess {

    /**
     * @var array 元数据 key => value 对
     */
    private $metadata = [];

    /**
     * 添加元数据
     * @param string $name
     * @param mixed $value
     */
    public function addMetadata($name, $value) {
        $this->metadata[$name] = $value;
    }

    /**
     * 返回所有元数据
     * @return array 
     */
    public function getAllMetadata() {
        return $this->metadata;
    }

    /**
     * 是否含有某个元数据
     * @param string $name 元数据名称
     * @return bool
     */
    public function has($name) {
        return isset($this->metadata[$name]);
    }

    /**
     * 获取某个元数据的值
     * @param string $name 元数据名称
     * @return mixed
     */
    public function get($name) {
        return isset($this->metadata[$name]) ? $this->metadata[$name] : null;
    }

    public function offsetExists($offset) {
        return isset($this->metadata[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->metadata[$offset]) ? $this->metadata[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        $this->metadata[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->metadata[$offset]);
    }

}
