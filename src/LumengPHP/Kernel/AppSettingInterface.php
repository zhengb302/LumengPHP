<?php

namespace LumengPHP\Kernel;

/**
 * APP设置接口<br />
 * 配置文件通常会遭到运维人员的特殊对待，以致于当需要更新配置文件时，流程变的复杂，效率变的低下
 * 所以那些固定不变的配置，最好不要放在配置文件里。
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface AppSettingInterface {

    /**
     * 取得服务配置
     * @return array 
     */
    public function getServiceSetting();

    /**
     * 取得扩展配置
     * @return array 
     */
    public function getExtensionSetting();

    /**
     * 取得应用根目录
     * @return string 
     */
    public function getRootDir();

    /**
     * 取得缓存目录
     * @return string 
     */
    public function getCacheDir();
}
