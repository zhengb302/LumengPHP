<?php

/*
 * 配置入口
 */

return array(
    //应用配置参数
    'parameters' => require(__DIR__ . '/parameters.php'),
    //应用核心配置
    'app' => array(
        //路由配置
        'router' => require(__DIR__ . '/routing.php'),
        //过滤器配置
        'filter' => require(__DIR__ . '/filter.php'),
        //服务配置
        'services' => require(__DIR__ . '/services.php'),
        //扩展列表
        'extensions' => require(__DIR__ . '/extensions.php'),
        //应用根目录
        'rootDir' => dirname(__DIR__),
        //缓存目录路径
        'cacheDir' => dirname(__DIR__) . '/var/cache',
    ),
    //数据库配置
    'database' => require(__DIR__ . '/database.php'),
);

