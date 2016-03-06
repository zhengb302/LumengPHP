<?php

namespace LumengPHP\Filter;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Filter构造程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FilterBuilder {

    /**
     * @var AppContext 
     */
    private $appContext;

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var Request 
     */
    private $request;

    /**
     * @var Response 
     */
    private $response;

    /**
     * @var array 过滤器参数，关联数组
     */
    private $parameters;

    public function __construct(AppContext $appContext, AppConfig $appConfig, Request $request) {
        $this->appContext = $appContext;
        $this->appConfig = $appConfig;
        $this->request = $request;
    }

    public function setResponse(Response $response) {
        $this->response = $response;
    }

    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * 返回filter实例
     * @param string $filterClass
     * @return Filter
     */
    public function getFilter($filterClass) {
        $filter = new $filterClass($this->appContext, $this->appConfig, $this->request);

        if ($this->response instanceof Response) {
            $filter->setResponse($this->response);
        }

        if (!empty($this->parameters)) {
            $filter->setParameters($this->parameters);
        }

        return $filter;
    }

}
