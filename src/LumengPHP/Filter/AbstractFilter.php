<?php

namespace LumengPHP\Filter;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 过滤器抽象基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class AbstractFilter implements Filter {

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

    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppContext() {
        return $this->appContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppConfig() {
        return $this->appConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($paramName) {
        return isset($this->parameters[$paramName]) ?
                $this->parameters[$paramName] : null;
    }

}
