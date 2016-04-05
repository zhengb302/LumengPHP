<?php

/*
 * "tests\Commands\PropertyInjectionAwareCommand"类的属性注入dump文件
 */

return array(
    array(
        'property' => 'uid',
        'container' => 'query',
        'key' => 'user_id',
    ),
    array(
        'property' => 'name',
        'container' => 'request',
        'key' => 'name',
    ),
    array(
        'property' => 'password',
        'container' => 'request',
        'key' => 'password',
    ),
    array(
        'property' => 'age',
        'container' => 'request',
        'key' => 'userAge',
    ),
    array(
        'property' => 'logger',
        'container' => 'service',
        'key' => 'logger',
    ),
);
