<?php

namespace LumengPHP\Kernel;

/**
 * session抽象基类
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class SessionBase implements Session {

    public function offsetExists($offset) {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset) {
        return isset($_SESSION[$offset]) ? $_SESSION[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        $_SESSION[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($_SESSION[$offset]);
    }

}
