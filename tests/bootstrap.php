<?php

$autoloader = require(dirname(__DIR__) . '/vendor/autoload.php');
$autoloader->add('tests\\', dirname(__DIR__));

//定义测试根目录
define('TEST_ROOT', __DIR__);
