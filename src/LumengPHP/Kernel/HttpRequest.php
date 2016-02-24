<?php

namespace LumengPHP\Kernel;

/**
 * Description of Request
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 * 
 * @property ParameterBag $queryString GET请求参数
 * @property ParameterBag $post POST请求参数
 */
class HttpRequest {

    /**
     * @var ParameterBag GET请求参数
     */
    private $queryString;

    /**
     * @var ParameterBag POST请求参数
     */
    private $post;

    public function __construct(array $queryStringData, array $postData) {
        $this->queryString = new ParameterBag($queryStringData);
        $this->post = new ParameterBag($postData);
    }

    /**
     * 
     * @return ParameterBag
     */
    public function getQueryString() {
        return $this->queryString;
    }

    /**
     * 
     * @return ParameterBag
     */
    public function getPost() {
        return $this->post;
    }

    public function __get($name) {
        $methodName = 'get' . ucfirst($name);
        if (!method_exists($this, $methodName)) {
            trigger_error('proterty not exists', E_USER_ERROR);
        }

        return $this->$methodName();
    }

}
