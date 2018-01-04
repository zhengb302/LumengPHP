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

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string HTTP method
     */
    private $method;

    /**
     * @var string HTTP Request Scheme
     */
    private $requestScheme;

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
        //HTTP请求方法，转化为大写
        $this->method = strtoupper($this->server['REQUEST_METHOD']);

        //http or https，转化为小写
        $this->requestScheme = isset($this->server['REQUEST_SCHEME']) ? strtolower($this->server['REQUEST_SCHEME']) : 'http';
    }

    /**
     * 从超全局变量中创建一个<b>Request</b>实例
     * 
     * @return Request
     */
    public static function createFromGlobals() {
        $request = new Request($_GET, $_POST, $_REQUEST, $_COOKIE, $_FILES, $_SERVER);

        //session实例
        $request->setSession(new SimpleSession());

        return $request;
    }

    public function getQueryString() {
        return $this->server['QUERY_STRING'];
    }

    public function getRequestUri() {
        return $this->server['REQUEST_URI'];
    }

    public function getPathInfo() {
        return $this->server['PATH_INFO'];
    }

    /**
     * GET、POST、PUT、DELETE等，大写
     * 
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * http or https，小写
     * 
     * @return string
     */
    public function getRequestScheme() {
        return $this->requestScheme;
    }

    /**
     * 返回<b>SessionInterface</b>实例
     * 
     * @return SessionInterface
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * 设置<b>SessionInterface</b>实例
     * 
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session) {
        $this->session = $session;
    }

}
