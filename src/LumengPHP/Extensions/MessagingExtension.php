<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\Extension;
use LumengPHP\Messaging\ConnectionManager;

/**
 * 消息服务代理扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class MessagingExtension extends Extension {

    public function getName() {
        return 'LumengPHP-Messaging';
    }

    public function load() {
        $messagingConfig = $this->appContext->getConfig('messaging');

        //如果消息服务配置为空，则表示不需要消息服务，退出
        if (empty($messagingConfig)) {
            return;
        }

        //把连接管理器注册为服务
        $this->container->registerService('messagingConnManager', function($container) {
            //获取消息服务配置
            $appContext = $container->get('appContext');
            $messagingConfig = $appContext->getConfig('messaging');

            //获取日志服务
            $loggerName = $messagingConfig['loggerName'];
            $logger = $loggerName ? $container->get($loggerName) : null;

            return new ConnectionManager($messagingConfig['connections'], $logger);
        });

        //把各个连接注册为服务，服务名为连接名
        foreach (array_keys($messagingConfig['connections']) as $connName) {
            $this->container->registerService($connName, function($container) use ($connName) {
                return $container->get('messagingConnManager')->getConnection($connName);
            });
        }
    }

}
