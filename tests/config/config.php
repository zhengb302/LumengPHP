<?php

/*
 * 配置
 */

return array(
    //框架配置
    'framework' => array(
        'defaultLocale' => 'zh',
        'router' => require(__DIR__ . '/routing.php'),
        'filter' => require(__DIR__ . '/filter.php'),
    ),
    //服务配置
    'services' => require(__DIR__ . '/services.php'),
);

