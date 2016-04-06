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
     * Constructor.
     *
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = array()) {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $this->content = json_encode($data);

        $this->headers->set('Content-Type', 'application/javascript');
    }

}
