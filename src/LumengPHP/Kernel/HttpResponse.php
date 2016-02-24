<?php

namespace LumengPHP\Kernel;

/**
 * 
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class HttpResponse {

    const MIME_HTML = 'text/html';
    const MIME_TEXT = 'text/plain';
    const MIME_JSON = 'application/json';

    /**
     * @var string 内容类型，如：text/html
     */
    protected $contentType;

    /**
     * @var string 内容编码
     */
    protected $charset;

    /**
     * @var string 响应内容
     */
    protected $content;

    public function __construct() {
        $this->contentType = self::MIME_HTML;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->contentType = self::MIME_JSON;
        }
        $this->charset = 'utf-8';
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function getCharset() {
        return $this->charset;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    public function setCharset($charset) {
        $this->charset = $charset;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
