<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\HttpAppSettingInterface;

/**
 * 提供一些基本功能的抽象类
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
abstract class AbstractRouter implements RouterInterface {

    /**
     * @var AppContextInterface
     */
    protected $appContext;

    /**
     * @var HttpAppSettingInterface 
     */
    protected $appSetting;

    /**
     * @var mixed 
     */
    protected $routingConfig;

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
        $this->appSetting = $appContext->getAppSetting();
        $this->routingConfig = $this->appSetting->getRoutingConfig();
    }

}
