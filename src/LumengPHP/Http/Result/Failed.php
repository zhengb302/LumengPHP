<?php

namespace LumengPHP\Http\Result;

/**
 * 失败的结果<br />
 * 当一个<b>Result</b>对象的status小于或等于0时，则表示失败
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Failed extends Result {

    /**
     * 构造Failed对象
     * @param string $msg 失败消息
     * @param array $data (携带的)数据
     */
    public function __construct($msg = '', array $data = []) {
        parent::__construct(0, $msg, $data);
    }

}
