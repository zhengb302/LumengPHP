<?php

namespace LumengPHP\Http\Events;

use LumengPHP\Http\Result\Result;

/**
 * HTTP Result对象被创建事件
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
