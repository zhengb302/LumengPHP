<?php

namespace LumengPHP\Kernel\Command;

use LumengPHP\Kernel\AppContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * 命令接口<br />
 * 命令是封装用户请求的地方
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface Command {

    /**
     * 初始化方法<br />
     * execute方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     * 
     * @param AppContext $appContext 应用环境对象
     */
    public function init(AppContext $appContext);

    /**
     * 执行命令并返回Response
     * 
     * @param Request $request 请求对象
     * @return Response
     * @throws Exception
     */
    public function execute(Request $request);
}
