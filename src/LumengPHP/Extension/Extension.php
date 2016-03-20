<?php

namespace LumengPHP\Extension;

use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 扩展接口<br />
 * 在扩展里边，可以注册服务、做一些其他事情等
 * @author Lumeng <zhengb302@163.com>
 */
interface Extension {

    /**
     * 加载扩展
     * @param AppContext $appContext
     * @param ServiceContainer $serviceContainer
     */
    public function load(AppContext $appContext, ServiceContainer $serviceContainer);
}
