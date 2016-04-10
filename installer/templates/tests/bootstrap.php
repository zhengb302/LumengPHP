<?php

//定义常量
define('TEST_ROOT', __DIR__);
define('TEST_RESOURCES_DIR', TEST_ROOT . '/resources');

$autoloader = require(dirname(__DIR__) . '/vendor/autoload.php');
$autoloader->add('tests\\', dirname(__DIR__));

//BaseDatabaseTestCase要用到"$connectionConfigs"
$configs = require(__DIR__ . '/config/config.php');
$connectionConfigs = $configs['database']['connections'];

//初始化测试环境
LumengPHP\Test\TestStudio::initialize(__DIR__ . '/config/config.php');
