<?php

namespace LumengPHP\Kernel\Filter;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\Response;

/**
 * 过滤器基类
 * @author Lumeng <zhengb302@163.com>
 */
abstract class Filter implements FilterInterface {

    /**
     * @var AppContextInterface AppContext实例
     */
    protected $appContext;

    /**
     * @var Request Request实例
     */
    protected $request;

    /**
     * @var Response Response实例。在"pre filter"阶段，不会注入Response实例，
     * 所以此属性为null。在"post filter"阶段，此属性为相应的Response实例。
     */
    protected $response;

    public function setAppContext(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function setResponse(Response $response) {
        $this->response = $response;
    }

    /**
     * init方法默认实现
     * @see FilterInterface::init
     */
    public function init() {
        
    }

}
