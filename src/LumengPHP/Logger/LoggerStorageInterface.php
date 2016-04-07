<?php

namespace LumengPHP\Logger;

/**
 * 日志底层存储接口
 * @author Lumeng <zhengb302@163.com>
 */
interface LoggerStorageInterface {

    /**
     * 向日志存储中添加日志
     * @param string $level 日志级别
     * @param string $message 日志消息
     */
    public function addLog($level, $message);

    /**
     * 回刷内存中的日志数据到底层存储中
     */
    public function flush();
}
