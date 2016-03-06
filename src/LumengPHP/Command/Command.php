<?php

namespace LumengPHP\Command;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
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
     * 返回AppContext实例
     * @return AppContext
     */
    public function getAppContext();

    /**
     * 返回AppConfig实例
     * @return AppConfig
     */
    public function getAppConfig();

    /**
     * 返回Request实例
     * @return Request
     */
    public function getRequest();

    /**
     * 初始化方法<br />
     * execute方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     */
    public function init();

    /**
     * 执行命令并返回Response
     * @return Response
     * @throws Exception
     */
    public function execute();
}
