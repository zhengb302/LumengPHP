<?php

namespace LumengPHP\Kernel\DependencyInjection;

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
     * @var array 服务配置Map，格式：service name => service config/callback
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
     * @param array $configs 服务配置Map，格式：service name => service config/callback
     */
    public function __construct(array $configs) {
        $this->configs = $configs;
        $this->services = [];
        $this->serviceBuilder = new ServiceBuilder($this);
    }

    /**
     * 根据服务名称检查服务容器中是否存在相应的服务对象
     * @param string $serviceName 服务名称
     * @return boolean 存在则返回true，不存在则返回false
     */
    public function has($serviceName) {
        return isset($this->services[$serviceName]) ||
                isset($this->configs[$serviceName]);
    }

    /**
     * 根据服务名称返回一个服务对象
     * @param string $serviceName 服务名称
     * @return mixed|null 一个服务对象。如果服务不存在，返回null
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
        if (!is_callable($serviceConfig) && !is_array($serviceConfig)) {
            throw new ServiceContainerException("无效的服务配置，服务名称：{$serviceName}");
        }

        $this->services[$serviceName] = $this->serviceBuilder->build($serviceConfig);
    }

    /**
     * 注册服务<br />
     * 如果服务容器中已经存在名称相同的服务，则会覆盖原来的服务对象
     * @param string $serviceName 服务名称
     * @param mixed $serviceInstance 服务对象实例、或者是一个回调
     * @throws ServiceContainerException
     */
    public function register($serviceName, $serviceInstance) {
        if (!is_object($serviceInstance) && !is_callable($serviceInstance)) {
            throw new ServiceContainerException("{$serviceName} is not a valid service.");
        }

        //unset the old one
        $this->services[$serviceName] = null;
        unset($this->services[$serviceName]);

        //unset the old one
        $this->configs[$serviceName] = null;
        unset($this->configs[$serviceName]);

        if (is_object($serviceInstance)) {
            //set the new one
            $this->services[$serviceName] = $serviceInstance;
            return;
        }

        if (is_callable($serviceInstance)) {
            //set the new one
            $this->configs[$serviceName] = $serviceInstance;
            return;
        }
    }

}
