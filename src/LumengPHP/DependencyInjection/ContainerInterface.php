<?php

namespace LumengPHP\DependencyInjection;

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
}
