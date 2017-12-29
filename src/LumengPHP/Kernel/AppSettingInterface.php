<?php

namespace LumengPHP\Kernel;

/**
 * APP配置接口
 * 
 * 配置文件通常会遭到运维人员的特殊对待，以致于当需要更新配置文件时，流程变的复杂，效率变的低下
 * 所以那些固定不变的配置，最好不要放在配置文件里。
 * <b>AppSettingInterface</b>的出现，就是为了减少用于配置应用而存放在配置文件里的配置数量，
 * 将原本存放在配置文件里的、不会随环境变化而变化的配置转移到<b>配置类</b>里。
 * 那些会跟随环境变化而变化的配置，借助<b>Dotenv</b>库，剥离出来存放在已被版本库忽略的env文件里，
 * 这样提交代码的时候就不会将这些配置提交到版本库里。
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface AppSettingInterface {

    /**
     * 取得服务配置
     * 
     * 格式：服务名称 => 服务配置
     * 
     * 示例：
     * <pre>
     * [
     *     'foo' => [
     *         'class' => \MyApp\Somewhere\Foo:class,
     *     ],
     *     'bar' => [
     *         'class' => \MyApp\Somewhere\Boo:class,
     *     ],
     * ]
     * </pre>
     * 
     * @return array 
     */
    public function getServices();

    /**
     * 取得扩展配置
     * 
     * 示例：
     * <pre>
     * [
     *     \LumengPHP\Extensions\DatabaseExtension::class,
     *     \LumengPHP\Extensions\FooExtension::class,
     * ]
     * </pre>
     * 
     * @return array 
     */
    public function getExtensions();

    /**
     * 取得Job队列配置
     * 
     * 格式：队列名称 => 队列服务配置
     * 
     * 示例：
     * <pre>
     * [
     *     'fooQueue' => [
     *         'class' => \LumengPHP\Components\Queue\JobRedisQueue:class,
     *         'constructor-args' => ['redis conn', 'some queue name', 1500],
     *     ],
     *     'barQueue' => [
     *         'class' => \LumengPHP\Components\Queue\JobRedisQueue:class,
     *         'constructor-args' => ['redis conn', 'some queue name'],
     *     ],
     * ]
     * </pre>
     * 
     * @return array
     */
    public function getJobQueues();

    /**
     * 取得事件配置
     * 
     * 格式：事件类的全限定名称 => 事件监听器列表
     * 
     * 示例：
     * <pre>
     * [
     *     HttpEnd::class => [
     *         HttpEndEvtListener::class,
     *     ],
     *     UserAuthFailed::class => [
     *         UserAuthFailedSmsNotification::class,
     *         UserAuthFailedEmailNotification::class,
     *     ],
     * ]
     * </pre>
     * 
     * @return array 事件配置。如果应用不支持事件，则应该返回一个空数组
     */
    public function getEvents();

    /**
     * 取得应用根目录
     * 
     * @return string 
     */
    public function getRootDir();

    /**
     * 取得运行时目录
     * 
     * @return string 
     */
    public function getRuntimeDir();
}
