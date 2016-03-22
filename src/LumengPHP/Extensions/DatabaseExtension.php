<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\Extension;
use LumengPHP\Db\ConnectionManager;

/**
 * 数据库扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class DatabaseExtension implements Extension {

    public function getName() {
        return 'LumengPHP-Db';
    }

    public function load(AppContext $appContext, ServiceContainer $serviceContainer) {
        $dbConfigs = $appContext->getConfig('database');

        //如果数据库配置为空，则表示不需要数据库，退出
        if (empty($dbConfigs)) {
            return;
        }

        ConnectionManager::create($dbConfigs);

        //把各个连接注册为服务，服务名为连接名
        foreach (array_keys($dbConfigs) as $connName) {
            $serviceContainer->registerService($connName, function($container) use ($connName) {
                $logger = $container->get('logger');
                return ConnectionManager::getInstance()
                                ->getConnection($connName, $logger);
            });
        }
    }

}
