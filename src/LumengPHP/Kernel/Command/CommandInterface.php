<?php

namespace LumengPHP\Kernel\Command;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\Response;
use Exception;

/**
 * 命令接口<br />
 * 命令是封装用户请求的地方
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface CommandInterface {

    /**
     * 此方法供外部程序注入AppContext实例
     * @param AppContextInterface $appContext
     */
    public function setAppContext(AppContextInterface $appContext);

    /**
     * 此方法供外部程序注入Request实例
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * 初始化方法<br />
     * execute方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     */
    public function init();

    /**
     * 执行命令并返回Response
     * 
     * @return Response
     * @throws Exception
     */
    public function execute();
}
