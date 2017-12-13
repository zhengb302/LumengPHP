<?php

namespace LumengPHP\Http\Result;

use Exception;

/**
 * 简单的结果处理器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleResultHandler implements ResultHandlerInterface {

    public function handleReturn($return) {
        //控制器方法可以直接返回一个ResultInterface实例
        if ($return instanceof ResultInterface) {
            $result = $return;
        }
        //啥都没返回，或者返回null
        elseif (is_null($return)) {
            $result = new Success();
        }
        //或者直接返回一个数据，这是执行成功的一种表现
        else {
            $result = new Success('', $return);
        }

        return $result;
    }

    public function handleException(Exception $ex) {
        $result = new Failed($ex->getMessage());

        $exCode = $ex->getCode();
        if ($exCode < 0) {
            $result->setStatus($exCode);
        }

        return $result;
    }

    public function handleResult(ResultInterface $result) {
        header('Content-Type:application/json; charset=utf-8');
        echo $result;
    }

}
