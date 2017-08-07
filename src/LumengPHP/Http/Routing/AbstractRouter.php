<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Kernel\AppContextInterface;

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

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

}
