<?php

namespace LumengPHP\Kernel;

use LumengPHP\Kernel\DependencyInjection\ContainerInterface;

/**
 * AppContext接口实现
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppContext implements AppContextInterface {

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var ContainerInterface 服务容器
     */
    private $serviceContainer;

    public function __construct(AppConfig $appConfig, ContainerInterface $serviceContainer) {
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
    public function getParameter($key) {
        return $this->appConfig->get("parameters.{$key}");
    }

    /**
     * {@inheritdoc}
     */
    public function getService($serviceName) {
        return $this->serviceContainer->get($serviceName);
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceContainer() {
        return $this->serviceContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir() {
        return $this->appConfig->get('app.rootDir');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir() {
        return $this->appConfig->get('app.cacheDir');
    }

}
