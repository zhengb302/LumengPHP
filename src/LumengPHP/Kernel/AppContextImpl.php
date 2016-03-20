<?php

namespace LumengPHP\Kernel;

use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * AppContext实现
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppContextImpl implements AppContext {

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var ServiceContainer 服务容器
     */
    private $serviceContainer;

    public function __construct(AppConfig $appConfig, ServiceContainer $serviceContainer) {
        $this->appConfig = $appConfig;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($key) {
        return $this->appConfig->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getService($serviceName) {
        return $this->serviceContainer->get($serviceName);
    }

}
