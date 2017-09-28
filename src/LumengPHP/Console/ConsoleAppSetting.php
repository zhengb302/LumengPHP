<?php

namespace LumengPHP\Console;

/**
 * 控制台应用配置
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ConsoleAppSetting implements ConsoleAppSettingInterface {

    /**
     * @var ConsoleAppSettingInterface 应用特定配置
     */
    private $appSetting;

    /**
     * 构造一个<b>ConsoleAppSetting</b>实例
     * @param ConsoleAppSettingInterface $appSetting 应用特定配置
     */
    public function __construct(ConsoleAppSettingInterface $appSetting) {
        $this->appSetting = $appSetting;
    }

    public function getServices() {
        return $this->appSetting->getServices() ?: [];
    }

    public function getExtensions() {
        return $this->appSetting->getExtensions() ?: [];
    }

    public function getEventConfig() {
        return $this->appSetting->getEventConfig() ?: [];
    }

    public function getRootDir() {
        return $this->appSetting->getRootDir();
    }

    public function getRuntimeDir() {
        return $this->appSetting->getRuntimeDir();
    }

    public function getCmdMapping() {
        return $this->appSetting->getCmdMapping() ?: [];
    }

}
