<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\AbstractExtension;
use LumengPHP\Db\ConnectionManager;
use LumengPHP\Db\Misc\ShortcutFunctionHelper;
use LumengPHP\Exceptions\InvalidConfigurationException;

/**
 * 数据库扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class DatabaseExtension extends AbstractExtension {

    public function getName() {
        return 'LumengPHP-Db';
    }

    public function load() {
        $dbConfig = $this->appContext->getConfig('database');

        //如果数据库配置为空，抛出异常
        if (empty($dbConfig)) {
            throw new InvalidConfigurationException('missing database config.');
        }

        //加载快捷函数
        require_once(ShortcutFunctionHelper::getPath());

        //把连接管理器注册为服务
        $this->container->register('connManager', function($container) {
            //获取数据库配置
            $appContext = $container->get('appContext');
            $dbConfig = $appContext->getConfig('database');

            //获取日志服务
            $loggerName = $dbConfig['loggerName'];
            $logger = $loggerName ? $container->get($loggerName) : null;

            return new ConnectionManager($dbConfig['connections'], $logger);
        });

        //把各个连接注册为服务，服务名为连接名
        foreach (array_keys($dbConfig['connections']) as $connName) {
            $this->container->register($connName, function($container) use ($connName) {
                return $container->get('connManager')->getConnection($connName);
            });
        }
    }

}
