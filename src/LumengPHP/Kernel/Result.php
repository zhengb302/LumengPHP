<?php

namespace LumengPHP\Kernel;

/**
 * 结果
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class Result {

    const SUCCESS = 1;
    const FAILURE = 0;

    /**
     * @var int 结果状态
     */
    private $status;

    /**
     * @var string 消息
     */
    private $msg;

    /**
     * @var array 更多数据
     */
    private $more;

    public function __construct($status, $msg = '', array $more = array()) {
        $this->status = $status;
        $this->msg = $msg;
        $this->more = $more;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getMsg() {
        return $this->msg;
    }

    public function getMore() {
        return $this->more;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function setMore(array $more) {
        $this->more = $more;
    }

    /**
     * 返回是否成功
     * @return boolean
     */
    public function isSuccess() {
        return $this->status == self::SUCCESS;
    }

    /**
     * 返回是否失败
     * @return boolean
     */
    public function isFailure() {
        return $this->status == self::FAILURE;
    }

    public function toJsonString() {
        return json_encode($this->toArray());
    }

    private function toArray() {
        return array(
            'status' => $this->status,
            'msg' => $this->msg,
            'more' => $this->more,
        );
    }

}
