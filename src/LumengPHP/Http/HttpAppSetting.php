<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppSettingInterface;
use LumengPHP\Http\Routing\SimpleRouter;
use LumengPHP\Http\Result\SimpleResultHandler;

/**
 * HTTP应用配置
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpAppSetting implements AppSettingInterface {

    /**
     * @var AppSettingInterface 应用特定配置
     */
    private $appSetting;

    /**
     * 构造一个<b>HttpAppSetting</b>实例
     * @param AppSettingInterface $appSetting 应用特定配置
     */
    public function __construct(AppSettingInterface $appSetting) {
        $this->appSetting = $appSetting;
    }

    public function getServiceSetting() {
        //http服务配置
        $httpAppServices = [
            'httpRouter' => [
                'class' => SimpleRouter::class,
                'constructor-args' => ['@appContext'],
            ],
            'httpResultHandler' => [
                'class' => SimpleResultHandler::class,
            ],
        ];

        //应用特定服务配置
        $appServices = $this->appSetting->getServiceSetting() ?: [];

        return array_merge($httpAppServices, $appServices);
    }

    public function getExtensionSetting() {
        //http扩展
        $httpAppExtensions = [];

        //应用特定扩展
        $appExtensions = $this->appSetting->getExtensionSetting() ?: [];

        return array_merge($httpAppExtensions, $appExtensions);
    }

    public function getRootDir() {
        return $this->appSetting->getRootDir();
    }

    public function getCacheDir() {
        return $this->appSetting->getCacheDir();
    }

}
