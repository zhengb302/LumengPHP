<?php

namespace LumengPHP\Kernel\Extension;

use LumengPHP\Kernel\AppContextInterface;

/**
 * 一个简单的Extension实现
 * @author Lumeng <zhengb302@163.com>
 */
abstract class AbstractExtension implements ExtensionInterface {

    /**
     * @var AppContextInterface AppContext实例
     */
    protected $appContext;

    public function setAppContext(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

}
