<?php

/*
 * 配置入口
 */

return [
    //应用配置参数
    'parameters' => require(__DIR__ . '/parameters.php'),
    //应用核心配置
    'app' => [
        //服务配置
        'services' => require(__DIR__ . '/services.php'),
        //扩展列表
        'extensions' => require(__DIR__ . '/extensions.php'),
        //应用根目录
        'rootDir' => dirname(__DIR__),
        //缓存目录路径
        'cacheDir' => dirname(__DIR__) . '/var/cache',
    ],
];
