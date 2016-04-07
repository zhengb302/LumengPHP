<?php

namespace LumengPHP\Misc;

use LumengPHP\Kernel\Response;

/**
 * JSON格式的响应内容
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JsonResponse extends Response {

    /**
     * @var array 保存原始数据
     */
    private $data;

    /**
     * Constructor.
     *
     * @param array $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct(array $data = array(), $status = 200, $headers = array()) {
        parent::__construct('', $status, $headers);

        $this->data = $data;

        $this->content = json_encode($data);

        $this->headers->set('Content-Type', 'application/javascript');
    }

    /**
     * 返回JSON形式的内容(即数组)
     * @return array
     */
    public function getJsonContent() {
        return $this->data;
    }

}
