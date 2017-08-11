<?php

namespace LumengPHP\Http\Result;

/**
 * 结果
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class Result {

    /**
     * @var int 结果状态
     */
    private $status;

    /**
     * @var string 消息
     */
    private $msg;

    /**
     * @var array (携带的)数据
     */
    private $data;

    public function __construct($status, $msg = '', array $data = []) {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getMsg() {
        return $this->msg;
    }

    public function getData() {
        return $this->data;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function __toString() {
        return json_encode([
            'status' => $this->status,
            'msg' => $this->msg,
            'data' => $this->data,
        ]);
    }

}
