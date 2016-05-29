<?php

namespace LumengPHP\Kernel\Extension;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 扩展基类
 * @author Lumeng <zhengb302@163.com>
 */
abstract class Extension implements ExtensionInterface {

    /**
     * @var AppContextInterface AppContext实例
     */
    protected $appContext;

    /**
     * @var ServiceContainer 服务容器
     */
    protected $container;

    public function setAppContext(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

    public function setServiceContainer(ServiceContainer $container) {
        $this->container = $container;
    }

}
