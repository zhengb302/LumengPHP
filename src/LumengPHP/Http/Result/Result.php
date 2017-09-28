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
     * @var array (携带的)数据。这个数据是比较正式的数据。
     */
    private $data;

    /**
     * @var array 更多数据。用于携带除了正式数据之外的数据，如调试信息等。
     */
    private $more;

    public function __construct($status, $msg = '', array $data = []) {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
        $this->more = [];
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

    public function getMore() {
        return $this->more;
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

    public function setMore(array $more) {
        $this->more = $more;
    }

    /**
     * 返回字符串形式的Result对象，子类可以覆盖此方法用以定制特定的字符串格式。
     * 
     * @return string
     */
    public function __toString() {
        $result = [
            'status' => $this->status,
            'msg' => $this->msg,
            'data' => $this->data,
        ];
        if ($this->more) {
            $result['more'] = $this->more;
        }

        return json_encode($result);
    }

}
