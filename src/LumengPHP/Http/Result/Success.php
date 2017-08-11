<?php

namespace LumengPHP\Http\Result;

/**
 * 成功的结果<br />
 * 当一个<b>Result</b>对象的status等于1时，则表示成功
 * 
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Success extends Result {

    /**
     * 构造Success对象
     * <pre>
     * Usage：
     * //场景1：只返回数据而不包含消息，可以直接返回一个array
     * return ['name' => '张三', 'age' => 18];
     * 
     * //场景2：只返回成功消息
     * return new Success('执行成功！');
     * 
     * //场景3：返回成功消息及数据
     * return new Success('执行成功！', ['name' => '张三', 'age' => 18]);
     * </pre>
     * @param string $msg 成功消息
     * @param array $data (携带的)数据
     */
    public function __construct($msg = '', array $data = []) {
        parent::__construct(1, $msg, $data);
    }

}
