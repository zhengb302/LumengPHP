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
class DatabaseExtension extends Extension {

    public function getName() {
        return 'LumengPHP-Db';
    }

    public function load() {
        $dbConfig = $this->appContext->getConfig('database');

        //如果数据库配置为空，则表示不需要数据库，退出
        if (empty($dbConfig)) {
            return;
        }

        //加载快捷函数
        require_once(ShortcutFunctionHelper::getPath());

        //把连接管理器注册为服务
        $this->container->registerService('connManager', function($container) {
            //获取日志服务
            $appContext = $container->get('appContext');
            $dbConfig = $appContext->getConfig('database');
            $loggerName = $dbConfig['loggerName'];
            $logger = $loggerName ? $container->get($loggerName) : null;

            return new ConnectionManager($dbConfig['connections'], $logger);
        });

        //把各个连接注册为服务，服务名为连接名
        foreach (array_keys($dbConfig['connections']) as $connName) {
            $this->container->registerService($connName, function($container) use ($connName) {
                return $container->get('connManager')->getConnection($connName);
            });
        }
    }

}
