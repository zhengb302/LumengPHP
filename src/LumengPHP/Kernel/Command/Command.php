<?php

namespace LumengPHP\Kernel\Command;

use LumengPHP\DependencyInjection\PropertyInjection\PropertyInjectionAwareTrait;
use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Request;

/**
 * 命令接口<br />
 * 命令是封装用户请求的地方
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class Command implements CommandInterface {

    use PropertyInjectionAwareTrait;

    /**
     * @var AppContext AppContext实例
     */
    protected $appContext;

    /**
     * @var Request Request实例
     */
    protected $request;

    public function setAppContext(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * init方法默认实现
     * @see CommandInterface::init
     */
    public function init() {
        
    }

}
