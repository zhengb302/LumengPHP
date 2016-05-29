<?php

namespace LumengPHP\Kernel\Command;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Request;

/**
 * 命令基类<br />
 * 命令是封装用户请求的地方
 * @author zhengluming <luming.zheng@baozun.cn>
 */
abstract class Command implements CommandInterface {

    /**
     * @var AppContextInterface AppContext实例
     */
    protected $appContext;

    /**
     * @var Request Request实例
     */
    protected $request;

    public function setAppContext(AppContextInterface $appContext) {
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
