<?php

namespace LumengPHP\Kernel;

/**
 * APP设置接口<br />
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
     * @return array 
     */
    public function getServices();

    /**
     * 取得扩展配置
     * 
     * @return array 
     */
    public function getExtensions();

    /**
     * 取得事件配置<br />
     * 格式：事件名称/事件类的全限定名称 => 事件监听器列表
     * 示例：
     * [
     *     'system.end' => [
     *         SystemEndEvtListener::class,
     *     ],
     *     UserAuthFailed::class => [
     *         UserAuthFailedSmsNotification::class,
     *         UserAuthFailedEmailNotification::class,
     *     ],
     * ]
     * 
     * @return array 事件配置。如果应用不支持事件，则应该返回一个空数组
     */
    public function getEventConfig();

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
