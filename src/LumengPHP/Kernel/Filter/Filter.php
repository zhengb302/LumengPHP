<?php

namespace LumengPHP\Kernel\Filter;

use LumengPHP\Kernel\AppContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 过滤器接口
 * @author Lumeng <zhengb302@163.com>
 */
interface Filter {

    /**
     * 初始化方法<br />
     * doFilter方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     * 
     * @param AppContext $appContext 应用环境对象
     */
    public function init(AppContext $appContext);

    /**
     * 执行过滤器动作<br />
     * 如果返回一个Response，则会直接跳过后续filter及命令的执行，
     * 做一些收尾动作之后结束执行
     * 
     * @param Request $request 请求对象
     * @param Response $response 响应对象。在"pre filter"里此参数为null
     * @return Response|null
     */
    public function doFilter(Request $request, Response $response = null);
}
