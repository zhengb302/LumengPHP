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
     * @var AppSettingInterface 应用特定配置
     */
    private $appSetting;

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var ContainerInterface 服务容器
     */
    private $serviceContainer;

    public function __construct(AppSettingInterface $appSetting, AppConfig $appConfig, ContainerInterface $serviceContainer) {
        $this->appSetting = $appSetting;
        $this->appConfig = $appConfig;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppSetting() {
        return $this->appSetting;
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
        return $this->appSetting->getRootDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir() {
        return $this->appSetting->getCacheDir();
    }

}
