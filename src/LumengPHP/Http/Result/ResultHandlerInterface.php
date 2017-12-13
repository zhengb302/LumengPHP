<?php

namespace LumengPHP\Http\Result;

use Exception;

/**
 * 结果处理器接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface ResultHandlerInterface {

    /**
     * 处理控制器方法返回结果，转换控制器方法返回结果为ResultInterface对象
     * 
     * @param mixed $return 控制器方法返回结果
     * @return ResultInterface
     */
    public function handleReturn($return);

    /**
     * 处理异常，转换异常对象为ResultInterface对象
     * 
     * @param Exception $ex 异常对象
     * @return ResultInterface
     */
    public function handleException(Exception $ex);

    /**
     * 处理结果
     * 
     * @param ResultInterface $result ResultInterface实例
     */
    public function handleResult(ResultInterface $result);
}
