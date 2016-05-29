<?php

namespace LumengPHP\Kernel\Filter;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\Response;

/**
 * 过滤器接口
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface FilterInterface {

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
     * 此方法供外部程序注入Response实例<br />
     * 在"pre filter"里不会调用此方法以注入Response实例
     * @param Response $response
     */
    public function setResponse(Response $response);

    /**
     * 初始化方法<br />
     * doFilter方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     */
    public function init();

    /**
     * 执行过滤器动作<br />
     * 如果返回一个Response，则会直接跳过后续filter及命令的执行，
     * 做一些收尾动作之后结束执行
     * 
     * @return Response|null
     */
    public function doFilter();
}
