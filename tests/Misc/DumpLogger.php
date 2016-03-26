<?php

namespace tests\Misc;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class DumpLogger extends \Psr\Log\AbstractLogger {

    public function log($level, $message, array $context = array()) {
        echo "level: {$level}, message: {$message}";
    }

}
