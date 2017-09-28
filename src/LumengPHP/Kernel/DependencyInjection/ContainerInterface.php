<?php

namespace LumengPHP\Kernel\DependencyInjection;

/**
 * 容器接口
 * @author Lumeng <zhengb302@163.com>
 */
interface ContainerInterface {

    /**
     * 根据服务名称检查服务容器中是否存在相应的服务对象
     * @param string $serviceName 服务名称
     * @return bool 存在则返回true，不存在则返回false
     */
    public function has($serviceName);

    /**
     * 根据服务名称获取一个服务对象
     * @param string $serviceName 服务名称
     * @return object|null 一个服务对象。如果服务不存在，则返回null
     */
    public function get($serviceName);

    /**
     * 注册服务<br />
     * 如果服务容器中已经存在名称相同的服务，则会覆盖原来的服务对象
     * @param string $serviceName 服务名称
     * @param mixed $serviceInstance 服务对象实例、或者是一个匿名函数
     * @throws ServiceContainerException
     */
    public function register($serviceName, $serviceInstance);
}
