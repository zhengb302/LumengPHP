<?php

namespace LumengPHP\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * 命令行命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Command extends SymfonyCommand {

    public function getStubDir() {
        return __DIR__ . '/stubs';
    }

}
