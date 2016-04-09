<?php

/*
 * 数据库配置
 */

return array(
    //日志服务名称
    'loggerName' => 'logger',
    //数据库连接配置
    'connections' => array(
        //连接名称 => 连接配置
        'db' => array(
            'class' => 'LumengPHP\Db\Connection\SimpleConnection',
            //表前缀，如：bbs_
            'tablePrefix' => 'bbs_',
            //数据库字符集
            'charset' => 'utf8',
            //数据库配置
            'dsn' => 'mysql:host=127.0.0.1;dbname=bbsdb',
            'username' => 'bbs',
            'password' => 'bbs',
        ),
    ),
);
