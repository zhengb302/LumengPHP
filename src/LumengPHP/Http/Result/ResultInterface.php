<?php

namespace LumengPHP\Http\Result;

/**
 * 结果接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface ResultInterface {

    /**
     * 实现类必须要实现此魔术方法
     * 
     * @return string 
     */
    public function __toString();
}
