<?php

namespace LumengPHP\Kernel;

use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\ExtensionInterface;
use LumengPHP\Kernel\Facade\Facade;

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
        $config = require($configFilePath);
        $this->appConfig = new AppConfig($config);

        $this->initServiceContainer();

        $this->appContext = new AppContext($this->appConfig, $this->container);

        $this->container->register('appContext', $this->appContext);

        Facade::setAppContext($this->appContext);

        //加载扩展
        $this->loadExtensions();
    }

    /**
     * 初始化服务容器
     */
    private function initServiceContainer() {
        $serviceConfigs = $this->appConfig->get('app.services');

        //服务配置要不不存在，要不就是个数组
        assert(is_array($serviceConfigs) || is_null($serviceConfigs));

        if (is_null($serviceConfigs)) {
            $serviceConfigs = array();
        }

        $this->container = new ServiceContainer($serviceConfigs);
    }

    /**
     * 加载扩展
     */
    private function loadExtensions() {
        $extensions = $this->appConfig->get('app.extensions');

        //扩展配置要不不存在，要不就是个数组
        assert(is_array($extensions) || is_null($extensions));

        if (empty($extensions)) {
            return;
        }

        foreach ($extensions as $extensionClass) {
            $extension = new $extensionClass();

            assert($extension instanceof ExtensionInterface);

            //注入AppContext和ServiceContainer
            $extension->setAppContext($this->appContext);
            $extension->setServiceContainer($this->container);

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
