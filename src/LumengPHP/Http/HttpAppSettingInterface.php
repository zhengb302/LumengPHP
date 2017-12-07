<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppSettingInterface;

/**
 * HTTP应用配置接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface HttpAppSettingInterface extends AppSettingInterface {

    /**
     * 取得拦截器配置
     * 
     * 格式：拦截器类全限定名称 => 拦截模式
     * 
     * 示例：
     * <pre>
     * [
     *     Profiler::class => '*',
     *     LogFlusher::class => '*',
     *     UserAuth::class => '*, ~/user/login, ~/other/help',
     * ]
     * </pre>
     * 
     * @return array
     */
    public function getInterceptors();

    /**
     * 取得路由配置
     * 
     * 应用根据使用的路由实现，返回相应的路由配置：可以是一个数组，也可以是一个字符串、对象实例，
     * 或者如果不需要路由配置，直接返回null。
     * 因为没有固定的格式，所以取名为“getRoutingConfig”而不是“getRoutings”
     * 
     * @return mixed 
     */
    public function getRoutingConfig();
}
