<?php

namespace LumengPHP\Kernel;

use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 应用程序上下文接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppContextInterface {

    /**
     * 取得应用配置数据
     * @see AppConfig
     * @param string $key 配置key
     * @return mixed|null 
     */
    public function getConfig($key);

    /**
     * 取得应用配置参数
     * @param string $key 参数key
     * @return string|null
     */
    public function getParameter($key);

    /**
     * 取得服务对象实例
     * @see ServiceContainer
     * @param string $serviceName 服务名称
     * @return mixed|null 一个服务对象。如果服务不存在，返回null
     */
    public function getService($serviceName);

    /**
     * 取得应用根目录路径<br />
     * 注意：结尾不带斜杠“/”
     * @return string
     */
    public function getRootDir();

    /**
     * 取得缓存目录路径<br />
     * 注意：结尾不带斜杠“/”
     * @return string
     */
    public function getCacheDir();
}
