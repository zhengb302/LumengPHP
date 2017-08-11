<?php

namespace LumengPHP\Http\Result;

/**
 * 结果处理器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface ResultHandlerInterface {

    /**
     * 处理结果
     * @param Result $result Result实例
     */
    public function handle(Result $result);
}
