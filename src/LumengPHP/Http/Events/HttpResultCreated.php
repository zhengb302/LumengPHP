<?php

namespace LumengPHP\Http\Events;

use LumengPHP\Http\Result\Result;

/**
 * HTTP Result对象被创建事件，此事件发生在Result对象被创建之后、处理之前
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpResultCreated {

    /**
     * @var Result
     */
    private $result;

    public function __construct(Result $result) {
        $this->result = $result;
    }

    /**
     * 
     * @return Result
     */
    public function getResult() {
        return $this->result;
    }

}
