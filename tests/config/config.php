<?php

/*
 * 配置入口
 */

return array(
    //框架配置
    'framework' => array(
        'defaultLocale' => 'zh',
        //路由配置
        'router' => require(__DIR__ . '/routing.php'),
        //过滤器配置
        'filter' => require(__DIR__ . '/filter.php'),
        //服务配置
        'services' => require(__DIR__ . '/services.php'),
        //扩展配置
        'extensions' => require(__DIR__ . '/extensions.php'),
    ),
    //数据库配置
    'database' => require(__DIR__ . '/database.php'),
);

