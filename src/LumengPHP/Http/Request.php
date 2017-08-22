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

    /**
     * 从超全局变量中创建一个<b>Request</b>实例
     * @return Request
     */
    public static function createFromGlobals() {
        $request = new Request($_GET, $_POST, $_REQUEST, $_COOKIE, $_FILES, $_SERVER);
        return $request;
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

    /**
     * 返回<b>SessionInterface</b>实例
     * @return SessionInterface
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * 设置<b>SessionInterface</b>实例
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session) {
        $this->session = $session;
    }

}
