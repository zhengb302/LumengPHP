<?php

/*
 * 路由配置
 */

return array(
    'homepage' => array(
        'path' => '/',
        'cmd' => '\tests\Commands\HomepageCommand',
    ),
    'product' => array(
        'path' => '/product/',
        'cmd' => '\tests\Commands\ShowProductListCommand',
    ),
    'showOrder' => array(
        'path' => '/order/showOrder/{id}/',
        'cmd' => '\tests\Commands\ShowOrderCommand',
    ),
    'backgroundThief' => array(
        'path' => '/backgroundThief',
        'cmd' => '\tests\Commands\BackgroundThiefCommand',
    ),
);

