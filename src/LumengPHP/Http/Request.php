<?php

namespace LumengPHP\Http;

/**
 * HTTP请求
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Request {

    /**
     * @var array GET请求参数
     */
    public $get;

    /**
     * @var array POST请求参数
     */
    public $post;

    /**
     * @var array $_REQUEST
     */
    public $request;

    /**
     * @var array $_COOKIE
     */
    public $cookies;

    /**
     * @var array $_FILES
     */
    public $files;

    /**
     * @var array $_SERVER
     */
    public $server;

    public function __construct($get, $post, $request, $cookies, $files, $server) {
        $this->get = $get;
        $this->post = $post;
        $this->request = $request;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;

        $this->init();
    }

    private function init() {
        
    }

    public function getQueryString() {
        return $this->server['QUERY_STRING'];
    }

    public function getRequestUri() {
        return $this->server['REQUEST_URI'];
    }

    /**
     * GET、POST、PUT、DELETE等，大写
     * @return string
     */
    public function getMethod() {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    /**
     * http or https，小写
     * @return string
     */
    public function getRequestScheme() {
        return $this->server['REQUEST_SCHEME'];
    }

}
