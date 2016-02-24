<?php

namespace LumengPHP\Command;

use \LumengPHP\Kernel\AppContext;
use \LumengPHP\Kernel\HttpRequest;

/**
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface Command {

    public function setAppContext(AppContext $appContext);

    public function setRequest(HttpRequest $request);

    public function init();

    /**
     * 执行命令并返回处理结果
     * @return Result
     */
    public function execute();
}
