<?php

namespace LumengPHP\Kernel\DependencyInjection;

/**
 * 容器接口
 * @author Lumeng <zhengb302@163.com>
 */
interface ContainerInterface {

    /**
     * 检查容器中是否存在键值为$key的对象
     * @param string $key
     * @return boolean 存在则返回true，不存在则返回false
     */
    public function has($key);

    /**
     * 获取一个对象
     * @param string $key
     * @return mixed|null 存在则返回相应的对象，不存在则返回null
     */
    public function get($key);

    /**
     * 注册服务<br />
     * 如果服务容器中已经存在名称相同的服务，则会覆盖原来的服务对象
     * @param string $serviceName 服务名称
     * @param mixed $serviceInstance 服务对象、服务配置或者是一个回调
     * @throws ServiceContainerException
     */
    public function register($serviceName, $serviceInstance);
}
