<?php

namespace LumengPHP\Kernel\Extension;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 扩展基类
 * @author Lumeng <zhengb302@163.com>
 */
abstract class Extension implements ExtensionInterface {

    /**
     * @var AppContext AppContext实例
     */
    protected $appContext;

    /**
     * @var ServiceContainer 服务容器
     */
    protected $container;

    public function setAppContext(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function setServiceContainer(ServiceContainer $container) {
        $this->container = $container;
    }

}
