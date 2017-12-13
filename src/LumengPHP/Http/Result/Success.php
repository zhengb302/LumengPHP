<?php

namespace LumengPHP\Http\Result;

/**
 * 成功的结果
 * 
 * 当一个<b>Result</b>对象的status等于1时，则表示成功
 * 
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Success extends Result {

    /**
     * 构造一个<b>Success</b>实例
     * 
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
     * 
     * @param string $msg 成功消息
     * @param mixed $data (携带的)数据
     */
    public function __construct($msg = '', $data = null) {
        parent::__construct(self::SUCCESS, $msg, $data);
    }

}
