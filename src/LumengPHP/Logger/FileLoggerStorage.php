<?php

namespace LumengPHP\Logger;

/**
 * 基于文件的日志
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FileLoggerStorage implements LoggerStorageInterface {

    /**
     * @var string 日志文件路径
     */
    private $loggerFile;

    /**
     * @var int 日志队列阈值，达到阈值就会回刷日志
     */
    private $threshold;

    /**
     * @var array 日志队列
     */
    private $logs = array();

    public function __construct($loggerFile, $threshold = 10) {
        $this->loggerFile = $loggerFile;
        $this->threshold = $threshold;
    }

    public function addLog($level, $message) {
        $this->logs[] = sprintf("[%s] [%s] %s", date('Y-m-d H:i:s'), $level, $message);

        if (count($this->logs) >= $this->threshold) {
            $this->flush();
        }
    }

    public function flush() {
        if (empty($this->logs)) {
            return;
        }

        $content = implode("\n", $this->logs) . "\n";
        file_put_contents($this->loggerFile, $content, FILE_APPEND);

        $this->logs = array();
    }

}
