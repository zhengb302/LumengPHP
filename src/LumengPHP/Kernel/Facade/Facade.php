<?php

namespace LumengPHP\Kernel\Facade;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceNotExistException;

/**
 * Facade基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class Facade {

    /**
     * @var AppContext 应用程序上下文对象
     */
    private static $appContext;

    /**
     * @var array service name => service instance
     */
    private static $serviceMap = array();

    public static function setAppContext(AppContext $appContext) {
        self::$appContext = $appContext;
    }

    /**
     * 返回Facade所对应的服务名称
     * @return string Facade所对应的服务名称
     */
    abstract protected static function getServiceName();

    public static function __callStatic($name, $arguments) {
        $serviceName = static::getServiceName();

        if (isset(self::$serviceMap[$serviceName])) {
            $instance = self::$serviceMap[$serviceName];
        } else {
            $instance = self::$appContext->getService($serviceName);
            if (is_null($instance)) {
                throw new ServiceNotExistException("{$serviceName} not exists.");
            }

            self::$serviceMap[$serviceName] = $instance;
        }

        $callback = array($instance, $name);
        return call_user_func_array($callback, $arguments);
    }

}
