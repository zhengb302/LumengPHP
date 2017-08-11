<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Http\Routing\RouterInterface;
use LumengPHP\Http\Routing\SimpleRouter;
use LumengPHP\Http\Result\ResultHandlerInterface;
use LumengPHP\Http\Result\SimpleResultHandler;

/**
 * Http配置
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpConfig {

    /**
     * 
     * @param AppContextInterface $appContext
     * @return RouterInterface
     */
    public function getHttpRouter(AppContextInterface $appContext) {
        return new SimpleRouter($appContext);
    }

    /**
     * 
     * @return ResultHandlerInterface
     */
    public function getHttpResultHandler() {
        return new SimpleResultHandler();
    }

}
