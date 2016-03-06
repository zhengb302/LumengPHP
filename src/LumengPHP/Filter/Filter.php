<?php

namespace LumengPHP\Filter;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 过滤器接口
 * @author Lumeng <zhengb302@163.com>
 */
interface Filter {

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
     * 返回Response实例
     * @return Response|null "pre filter"阶段通常会返回null，
     * "post filter"阶段则会返回相应的Response对象
     */
    public function getResponse();

    /**
     * 返回过滤器参数
     * @param string $paramName
     * @return string|null 若参数存在就返回其值，不存在则返回null
     */
    public function getParameter($paramName);

    /**
     * 初始化方法<br />
     * doFilter方法执行之前，init方法会被框架调用。
     * 在这里可以进行一些初始化操作
     */
    public function init();

    /**
     * 执行过滤器动作<br />
     * 如果返回一个Response，则会直接跳过后续的filter及命令的执行，
     * 做一些收尾动作之后结束执行
     * @return Response|null
     */
    public function doFilter();
}
