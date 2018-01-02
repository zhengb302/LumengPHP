<?php

namespace LumengPHP\Kernel\DependencyInjection;

use Closure;

/**
 * 依赖注入服务容器<br />
 * Usage:
 *   $configs = ...
 *   $serviceContainer = new ServiceContainer($configs);
 *   $logger = $serviceContainer->get('logger');
 *   $logger->log('some important information...');
 * 
 * @author Lumeng <zhengb302@163.com>
 */
class ServiceContainer implements ContainerInterface {

    /**
     *
     * @var array 服务配置Map，格式：service name => service config/匿名函数
     */
    private $configs;

    /**
     *
     * @var array 服务Map，格式：service name => service instance
     */
    private $services;

    /**
     * @var ServiceBuilder 服务对象实例构造器
     */
    private $serviceBuilder;

    /**
     * 使用服务配置构造一个服务容器
     * @param array $configs 服务配置Map，格式：service name => service config/匿名函数
     */
    public function __construct(array $configs) {
        $this->configs = $configs;
        $this->services = [];
        $this->serviceBuilder = new ServiceBuilder($this);
    }

    /**
     * 根据服务名称检查服务容器中是否存在相应的服务对象
     * @param string $serviceName 服务名称
     * @return bool 存在则返回true，不存在则返回false
     */
    public function has($serviceName) {
        return isset($this->services[$serviceName]) ||
                isset($this->configs[$serviceName]);
    }

    /**
     * 根据服务名称获取一个服务对象
     * @param string $serviceName 服务名称
     * @return object|null 一个服务对象。如果服务不存在，则返回null
     */
    public function get($serviceName) {
        if (isset($this->services[$serviceName])) {
            return $this->services[$serviceName];
        }

        if (isset($this->configs[$serviceName])) {
            $this->buildService($serviceName);
            return $this->services[$serviceName];
        }

        return null;
    }

    private function buildService($serviceName) {
        $serviceConfig = $this->configs[$serviceName];
        if (!($serviceConfig instanceof Closure) && !is_array($serviceConfig)) {
            throw new ServiceContainerException("无效的服务配置，服务名称：{$serviceName}");
        }

        $this->services[$serviceName] = $this->serviceBuilder->build($serviceConfig);
    }

    public function register($serviceName, $service) {
        if (!is_array($service) && !($service instanceof Closure) && !is_object($service)) {
            throw new ServiceContainerException("{$serviceName} is not a valid service.");
        }

        //unset the old one
        $this->services[$serviceName] = null;
        unset($this->services[$serviceName]);

        //unset the old one
        $this->configs[$serviceName] = null;
        unset($this->configs[$serviceName]);

        //如果是服务配置或回调函数
        if (is_array($service) || $service instanceof Closure) {
            //set the new one
            $this->configs[$serviceName] = $service;
            return;
        }

        //如果是服务对象实例
        //is_object判断要放在后边，不然回调函数也会返回true
        if (is_object($service)) {
            //set the new one
            $this->services[$serviceName] = $service;
            return;
        }
    }

}
