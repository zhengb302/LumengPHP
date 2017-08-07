<?php

namespace LumengPHP\Http;

/**
 * 简单会话类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleSession implements SessionInterface {

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

    public function clear() {
        $_SESSION = [];
    }

}
