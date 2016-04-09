<?php

/*
 * 应用程序入口文件
 */

require(dirname(__DIR__) . '/vendor/autoload.php');

use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\AppKernel;

//创建Request对象
$request = Request::createFromGlobals();

//创建应用核心对象
$kernel = new AppKernel(dirname(__DIR__) . '/config/config.php');

//处理请求并生成响应对象
$response = $kernel->handle($request);

//发送响应
$response->send();

$kernel->terminate($request, $response);
