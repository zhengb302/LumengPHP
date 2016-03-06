<?php

/*
 * 过滤器组件配置
 */

return array(
    'preFilters' => array(
        'hit-counter' => array(
            'class' => 'tests\Filters\HitCounterFilter',
            'parameters' => array(
                
            ),
        ),
        'user-auth' => array(
            'class' => 'tests\Filters\UserAuthFilter',
            'routes' => array(''),
            'parameters' => array(
                
            ),
        ),
    ),
    'postFilters' => array(
        
    ),
);
