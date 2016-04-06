<?php

namespace LumengPHP\Test;

use LumengPHP\Kernel\Response;

/**
 * 测试用的Response
 *
 * @author Lumeng <zhengb302@163.com>
 */
class TestResponse {

    /**
     * @var Response 
     */
    private $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    /**
     * 返回JSON形式的内容(即关联数组)。如果响应内容不是有效的JSON数据，则返回null
     * @return array|null
     */
    public function getJsonContent() {
        $content = $this->response->getContent();
        $json = json_decode($content, true);
        return is_array($json) ? $json : null;
    }

    public function __call($name, $arguments) {
        $callback = array($this->response, $name);
        return call_user_func_array($callback, $arguments);
    }

}
