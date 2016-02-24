<?php

namespace LumengPHP\Kernel;

/**
 * 
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class ParameterBag implements \ArrayAccess {

    /**
     * @var array 
     */
    private $parameters;

    public function __construct(array $parameters) {
        $this->parameters = $parameters;
    }

    public function get($name) {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    public function offsetExists($offset) {
        return isset($this->parameters[$offset]);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {
        $this->parameters[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->parameters[$offset]);
    }

    /**
     * 
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }

}
