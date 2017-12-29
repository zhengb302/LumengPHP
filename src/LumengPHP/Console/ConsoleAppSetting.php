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
     * 
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

    public function getJobQueues() {
        return $this->appSetting->getJobQueues() ?: [];
    }

    public function getEvents() {
        return $this->appSetting->getEvents() ?: [];
    }

    public function getRootDir() {
        return $this->appSetting->getRootDir();
    }

    public function getRuntimeDir() {
        return $this->appSetting->getRuntimeDir();
    }

    public function getCmds() {
        //Console应用系统自带的一些命令
        $defaultCmds = [
            'job:listen' => \LumengPHP\Console\Commands\Job\Listen::class,
        ];

        //应用特定的命令
        $appCmds = $this->appSetting->getCmds() ?: [];

        return array_merge($defaultCmds, $appCmds);
    }

}
