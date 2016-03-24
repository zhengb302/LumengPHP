<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\Extension;
use LumengPHP\Db\ConnectionManager;
use LumengPHP\Db\Misc\ShortcutFunctionHelper;

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

        //加载快捷函数
        require_once(ShortcutFunctionHelper::getPath());

        //把连接管理器注册为服务
        $serviceContainer->registerService('connManager', function($container) use ($dbConfigs) {
            $logger = $container->get('logger');
            return new ConnectionManager($dbConfigs, $logger);
        });

        //把各个连接注册为服务，服务名为连接名
        foreach (array_keys($dbConfigs) as $connName) {
            $serviceContainer->registerService($connName, function($container) use ($connName) {
                return $container->getService('connManager')->getConnection($connName);
            });
        }
    }

}
