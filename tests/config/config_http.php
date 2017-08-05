<?php

/*
 * http 执行环境的配置文件
 */

return array(
    //继承其他配置文件，只支持单根继承
    'extends' => __DIR__ . '/config.php',
    //应用核心配置
    'app' => array(
        'extensions' => array(
            \LumengPHP\Extensions\HttpExtension::class,
        ),
    ),
    //http配置
    'httpKernel' => array(
        //路由配置
        'router' => require(__DIR__ . '/routing.php'),
        //过滤器配置
        'filter' => require(__DIR__ . '/filter.php'),
    ),
);

