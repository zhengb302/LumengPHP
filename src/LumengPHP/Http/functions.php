<?php

/*
 * HTTP应用相关的通用函数
 */

use LumengPHP\Http\Request;
use LumengPHP\Http\Result\Success;

/**
 * 返回系统中封装了HTTP请求数据的<b>Request</b>实例
 * 
 * 注意：只在HTTP应用中有效
 * 
 * @return Request
 */
function request() {
    return service('request');
}

/**
 * 返回一个<b>Success</b>实例，表示成功的结果
 * @param string $msg 成功消息
 * @param array $data 数据
 * @return Success
 */
function success($msg = '', array $data = []) {
    return new Success($msg, $data);
}
