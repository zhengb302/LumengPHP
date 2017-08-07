<?php

namespace LumengPHP\Kernel;

/**
 * 属性注入器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface PropertyInjectorInterface {

    /**
     * 执行注入动作
     * @return void
     */
    public function inject();
}
