<?php

namespace LumengPHP\Http;

use LumengPHP\Http\Result\SimpleResultHandler;
use LumengPHP\Http\Routing\DefaultRouter;

/**
 * HTTP应用配置
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpAppSetting implements HttpAppSettingInterface {

    /**
     * @var HttpAppSettingInterface 应用特定配置
     */
    private $appSetting;

    /**
     * 构造一个<b>HttpAppSetting</b>实例
     * @param HttpAppSettingInterface $appSetting 应用特定配置
     */
    public function __construct(HttpAppSettingInterface $appSetting) {
        $this->appSetting = $appSetting;
    }

    public function getServices() {
        //HTTP应用服务配置
        $httpAppServices = [
            'httpRouter' => [
                'class' => DefaultRouter::class,
                'constructor-args' => ['@appContext'],
            ],
            'httpResultHandler' => [
                'class' => SimpleResultHandler::class,
            ],
        ];

        //应用特定服务配置
        $appServices = $this->appSetting->getServices() ?: [];

        return array_merge($httpAppServices, $appServices);
    }

    public function getExtensions() {
        return $this->appSetting->getExtensions() ?: [];
    }

    public function getRootDir() {
        return $this->appSetting->getRootDir();
    }

    public function getRuntimeDir() {
        return $this->appSetting->getRuntimeDir();
    }

    public function getInterceptors() {
        return $this->appSetting->getInterceptors() ?: [];
    }

    public function getRoutingConfig() {
        return $this->appSetting->getRoutingConfig();
    }

}
