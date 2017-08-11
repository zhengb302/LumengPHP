<?php

namespace LumengPHP\Http\Result;

/**
 * 失败的结果
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Failed extends Result {

    /**
     * 构造Failed对象
     * @param string $msg 失败消息
     * @param array $data (携带的)数据
     */
    public function __construct($msg = '', $code = 0, array $data = []) {
        parent::__construct($code, $msg, $data);
    }

}
