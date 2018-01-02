<?php

namespace LumengPHP\Kernel;

use LumengPHP\Kernel\DependencyInjection\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * 应用程序上下文接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface AppContextInterface {

    /**
     * 取得<b>AppSettingInterface</b>实例
     * 
     * @return AppSettingInterface
     */
    public function getAppSetting();

    /**
     * 取得应用配置数据
     * 
     * @see AppConfig
     * @param string $key 配置key
     * @return mixed|null
     */
    public function getConfig($key);

    /**
     * 取得服务对象实例
     * 
     * @see ContainerInterface
     * 
     * @param string $serviceName 服务名称
     * 
     * @throws NotFoundExceptionInterface  服务不存在
     * @throws ContainerExceptionInterface 获取服务对象的过程中发生错误
     * 
     * @return object 一个服务对象
     */
    public function getService($serviceName);

    /**
     * 取得服务容器实例
     * 
     * @return ContainerInterface 服务容器实例
     */
    public function getServiceContainer();

    /**
     * 取得应用根目录路径
     * 
     * 注意：结尾不带斜杠“/”
     * 
     * @return string
     */
    public function getRootDir();

    /**
     * 取得运行时目录路径
     * 
     * 注意：结尾不带斜杠“/”
     * 
     * @return string
     */
    public function getRuntimeDir();
}
