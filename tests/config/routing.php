<?php

/*
 * 路由配置
 */

return array(
    'homepage' => array(
        'path' => '/',
        '_cmd' => 'tests\Commands\HomepageCommand',
        'preFilters' => array(
            array(
                'class' => '',
            ),
        ),
    ),
    'product' => array(
        'path' => '/product/',
        '_cmd' => 'tests\Commands\ShowProductListCommand',
    ),
    'showOrder' => array(
        'path' => '/order/showOrder/{id}/',
        '_cmd' => 'tests\Commands\ShowOrderCommand',
    ),
);

