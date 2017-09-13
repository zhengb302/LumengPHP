<?php

namespace LumengPHP\Console;

/**
 * 命令行输入接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface InputInterface {

    /**
     * 返回所有的参数，第0个参数是命令名称本身
     * 
     * @return array
     */
    public function getArgs();

    /**
     * 返回指定的参数
     * 
     * @param int $index 参数索引(或位置)，从0开始。第0个参数是命令名称本身
     */
    public function getArg($index);
}
