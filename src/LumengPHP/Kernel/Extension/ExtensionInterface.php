<?php

namespace LumengPHP\Kernel\Extension;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 扩展接口，用以优雅的集成组件、扩展功能。<br />
 * 在扩展里边，可以配置组件、注册服务、做一些其他事情等。
 * 一个组件，如果配置起来比较简单，则可以作为一个服务；如果配置起来比较复杂，
 * 像database、logger、cache等，则可以作为扩展来加载。
 * @author Lumeng <zhengb302@163.com>
 */
interface ExtensionInterface {

    /**
     * 向扩展中注入AppContext实例
     * @param AppContext $appContext
     */
    public function setAppContext(AppContext $appContext);

    /**
     * 向扩展中注入ServiceContainer实例
     * @param ServiceContainer $container
     */
    public function setServiceContainer(ServiceContainer $container);

    /**
     * 返回扩展名称
     * @return string 扩展名称
     */
    public function getName();

    /**
     * 加载扩展
     */
    public function load();
}
