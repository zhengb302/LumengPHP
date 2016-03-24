<?php

namespace LumengPHP\Kernel;

use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 应用程序上下文接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppContext {

    /**
     * 取得应用配置数据
     * @see AppConfig
     * @param string $key 配置key
     * @return mixed|null 
     */
    public function getConfig($key);

    /**
     * 取得服务对象实例
     * @see ServiceContainer
     * @param string $serviceName 服务名称
     * @return mixed|null 一个服务对象。如果服务不存在，返回null
     */
    public function getService($serviceName);
}
