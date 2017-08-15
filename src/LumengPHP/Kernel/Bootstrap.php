<?php

namespace LumengPHP\Kernel;

use LumengPHP\DependencyInjection\ServiceContainer;
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
     * @var AppConfig AppConfig对象
     */
    private $appConfig;

    /**
     * @var ServiceContainer 服务容器
     */
    private $container;

    /**
     * @var AppContextInterface 
     */
    private $appContext;

    public function boot($configFilePath) {
        $dotenv = new Dotenv(dirname($configFilePath), 'env');
        $dotenv->load();

        $config = require($configFilePath);
        $this->appConfig = new AppConfig($config);

        $this->initServiceContainer();

        $this->appContext = new AppContext($this->appConfig, $this->container);
        $this->container->register('appContext', $this->appContext);

        //加载扩展
        $this->loadExtensions();
    }

    /**
     * 初始化服务容器
     */
    private function initServiceContainer() {
        $serviceConfigs = $this->appConfig->get('app.services');

        if (is_null($serviceConfigs)) {
            $serviceConfigs = [];
        }

        $this->container = new ServiceContainer($serviceConfigs);
    }

    /**
     * 加载扩展
     */
    private function loadExtensions() {
        $extensions = $this->appConfig->get('app.extensions');
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
