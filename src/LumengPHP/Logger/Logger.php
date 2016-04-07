<?php

namespace LumengPHP\Logger;

use Psr\Log\AbstractLogger;

/**
 * 日志类
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Logger extends AbstractLogger {

    /**
     * @var LoggerStorageInterface 日志底层存储对象
     */
    private $storage;

    public function __construct(LoggerStorageInterface $storage) {
        $this->storage = $storage;
    }

    public function log($level, $message, array $context = array()) {
        $this->storage->addLog($level, $message);
    }

}
