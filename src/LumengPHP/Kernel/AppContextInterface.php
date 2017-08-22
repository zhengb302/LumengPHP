<?php

namespace LumengPHP\Kernel;

use LumengPHP\Kernel\DependencyInjection\ContainerInterface;

/**
 * 应用程序上下文接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppContextInterface {

    /**
     * 取得<b>AppSettingInterface</b>实例
     * @return AppSettingInterface
     */
    public function getAppSetting();

    /**
     * 取得应用配置数据
     * @see AppConfig
     * @param string $key 配置key
     * @return mixed|null 
     */
    public function getConfig($key);

    /**
     * 取得服务对象实例
     * @see ContainerInterface
     * @param string $serviceName 服务名称
     * @return mixed|null 一个服务对象。如果服务不存在，返回null
     */
    public function getService($serviceName);

    /**
     * 取得服务容器实例
     * @return ContainerInterface 服务容器实例
     */
    public function getServiceContainer();

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
