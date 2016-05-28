<?php

/*
 * 消息代理配置
 */

return array(
    // 日志服务名称
    'loggerName' => 'logger',
    // 各消息代理连接配置
    'connections' => array(
        // 连接名称 => 连接配置
        'redis' => array(
            'class' => 'LumengPHP\Messaging\Connection\RedisConnection',
            'host' => '127.0.0.1',
            'port' => 6379,
        ),
        'rabbitmq' => array(
            'class' => 'LumengPHP\Messaging\Connection\RabbitMQConnection',
            'host' => '127.0.0.1',
            'port' => 5672,
            'username' => 'guest',
            'password' => 'guest',
        ),
    ),
);
