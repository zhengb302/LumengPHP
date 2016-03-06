<?php

/*
 * 配置
 */

return array(
    //框架配置
    'framework' => array(
        'defaultLocale' => 'zh',
        'router' => require(__DIR__ . '/routing.php'),
        'globalFilters' => require(__DIR__ . '/global_filters.php'),
    ),
);

