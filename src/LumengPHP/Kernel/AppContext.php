<?php

namespace LumengPHP\Kernel;

/**
 * 应用环境
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class AppContext {

    private static $instance;

    /**
     * @var HttpRequest 
     */
    private $request;

    /**
     * @var HttpResponse 
     */
    private $response;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Configuration 
     */
    private $configuration;

    private function __construct() {
        $this->response = new HttpResponse();
    }

    public function __clone() {
        trigger_error('AppContext不支持克隆操作!', E_USER_ERROR);
    }

    /**
     * 获取AppContext实例
     * @return AppContext
     */
    public static function getAppContext() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setRequest(HttpRequest $request) {
        $this->request = $request;
    }

    public function setSession(Session $session) {
        $this->session = $session;
    }

    public function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * 返回当前会话实例
     * @return Session 
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * 返回 HttpRequest 实例
     * @return HttpRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * 返回 HttpResponse 实例
     * @return HttpResponse
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * 获取配置程序
     * @return Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    public function sendResponse() {
        $contentType = 'Content-Type: ' . $this->response->getContentType() .
                '; charset=' . $this->response->getCharset();
        header($contentType);
        echo $this->response->getContent();
    }

}
