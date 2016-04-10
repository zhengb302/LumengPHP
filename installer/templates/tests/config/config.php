<?php

/*
 * 单元测试配置
 */

//正式配置
$appConfigs = require(dirname(dirname(__DIR__)) . '/config/config.php');

//单元测试特定的配置
$testConfigs = array(
    'database' => array(
        'loggerName' => 'logger',
        'connections' => array(
            'db' => array(
                'class' => 'LumengPHP\Db\Connection\SimpleConnection',
                'tablePrefix' => 'cbd_',
                //数据库字符集
                'charset' => 'utf8',
                //数据库配置
                'dsn' => 'mysql:host=127.0.0.1;dbname=shandjj_wuliu_test',
                'username' => 'wuliu_test',
                'password' => 'wuliu_test',
            ),
        ),
    ),
);

return array_merge($appConfigs, $testConfigs);

