<?php

namespace LumengPHP\Kernel;

/**
 * AppContext实现
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppContextImpl implements AppContext {

    /**
     * @var AppConfig 
     */
    private $appConfig;

    public function __construct(AppConfig $appConfig) {
        $this->appConfig = $appConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppConfig() {
        $this->appConfig;
    }

}
