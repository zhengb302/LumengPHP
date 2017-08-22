<?php

namespace LumengPHP\Kernel;

use LumengPHP\Kernel\DependencyInjection\ContainerInterface;
use LumengPHP\Kernel\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\ExtensionInterface;
use Dotenv\Dotenv;
use Exception;

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
     * @var AppSettingInterface 应用特定配置
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

        $config = require($configFilePath);
        $appConfig = new AppConfig($config);

        //构造服务容器
        $this->buildServiceContainer();

        $this->appContext = new AppContext($appSetting, $appConfig, $this->container);
        $this->container->register('appContext', $this->appContext);

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
