<?php

namespace LumengPHP\Http\Result;

/**
 * 简单的结果处理器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleResultHandler implements ResultHandlerInterface {

    public function handle(Result $result) {
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
    }

}
