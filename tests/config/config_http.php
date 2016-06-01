<?php

/*
 * http 执行环境的配置文件
 */

return array(
    //导入其他配置文件；当前文件中的配置的优先级高于被导入文件中的配置的优先级。
    'imports' => array(
        __DIR__ . '/config.php',
    ),
    //应用核心配置
    'app' => array(
        'extensions' => array(
            'LumengPHP\Extensions\HttpKernelExtension',
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

