<?php

/*
 * job配置
 */

return array(
    // 定义job通道
    'channels' => array(
        // 通道名称 => 通道配置
        'redisChannel' => array(
            // 消息代理连接名称
            'connectionName' => 'redis',
            //队列名称
            'queueName' => '',
        ),
        'rabbitmqChannel' => array(
            'connectionName' => 'rabbitmq',
            'queueName' => '',
        ),
    ),
    // 定义特定的job所使用的通道，未在这里出现的job，则会使用默认通道(即第一个通道)
    'jobChannel' => array(
        // job名称 => 通道名称
        'logJob' => 'redisChannel',
        'orderPushJob' => 'rabbitmqChannel',
    ),
);

