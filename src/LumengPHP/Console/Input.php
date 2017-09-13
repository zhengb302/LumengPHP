<?php

namespace LumengPHP\Console;

/**
 * InputInterface的默认实现
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Input implements InputInterface {

    /**
     * @var array 
     */
    private $args;

    public function __construct(array $args) {
        $this->args = $args;
    }

    public function getArgs() {
        return $this->args;
    }

    public function getArg($index) {
        return isset($this->args[$index]) ? $this->args[$index] : null;
    }

}
