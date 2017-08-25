<?php

use LumengPHP\Http\Result\Success;

/*
 * HTTP相关的通用函数
 */

/**
 * 返回一个<b>Success</b>实例，表示成功的结果
 * @param string $msg 成功消息
 * @param array $data 数据
 * @return Success
 */
function success($msg = '', array $data = []) {
    return new Success($msg, $data);
}
