<?php

namespace LumengPHP\Kernel;

use Dotenv\Dotenv;
use Exception;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\DependencyInjection\ContainerInterface;
use LumengPHP\Kernel\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\ExtensionInterface;

/**
 * 应用引导程序<br />
 * Usage:
 *     $bootstrap = new Bootstrap();
 *     $bootstrap->boot($configFilePath);
 * 
 *     $appContext = $bootstrap->getAppContext();
 *     // do something
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Bootstrap {

    /**
     * @var AppSettingInterface 应用配置对象
     */
    private $appSetting;

    /**
     * @var ContainerInterface 服务容器
     */
    private $container;

    /**
     * @var AppContextInterface 
     */
    private $appContext;

    public function boot(AppSettingInterface $appSetting, $configFilePath) {
        $this->appSetting = $appSetting;

        $dotenv = new Dotenv(dirname($configFilePath), 'env');
        $dotenv->load();

        //构造服务容器
        $this->buildServiceContainer();

        //构造 AppContextInterface 实例并注册为服务
        $config = require($configFilePath);
        $appConfig = new AppConfig($config);
        $this->appContext = new AppContext($appSetting, $appConfig, $this->container);
        $this->container->register('appContext', $this->appContext);

        //构造 ClassMetadataLoader 实例并注册为服务
        $classMetadataLoader = new ClassMetadataLoader($this->appContext);
        $this->container->register('classMetadataLoader', $classMetadataLoader);

        //加载扩展
        $this->loadExtensions();
    }

    /**
     * 构造服务容器
     */
    private function buildServiceContainer() {
        $serviceConfigs = $this->appSetting->getServices();

        if (is_null($serviceConfigs)) {
            $serviceConfigs = [];
        }

        $this->container = new ServiceContainer($serviceConfigs);
    }

    /**
     * 加载扩展
     */
    private function loadExtensions() {
        $extensions = $this->appSetting->getExtensions();
        if (empty($extensions)) {
            return;
        }

        foreach ($extensions as $extensionClass) {
            $extension = new $extensionClass();
            if (!$extension instanceof ExtensionInterface) {
                throw new Exception("“{$extensionClass}”不是“ExtensionInterface”的实例");
            }

            //注入AppContext
            $extension->setAppContext($this->appContext);

            //加载扩展
            $extension->load();
        }
    }

    /**
     * 返回AppContext实例
     * @return AppContextInterface
     */
    public function getAppContext() {
        return $this->appContext;
    }

}
