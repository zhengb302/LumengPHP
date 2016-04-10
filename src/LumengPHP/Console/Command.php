<?php

namespace LumengPHP\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use LumengPHP\Kernel\AppContext;

/**
 * 命令行命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Command extends SymfonyCommand {

    /**
     * 
     * @return Application
     */
    public function getApplication() {
        return parent::getApplication();
    }

    /**
     * 返回AppContext实例
     * @return AppContext
     */
    protected function getAppContext() {
        return $this->getApplication()->getAppContext();
    }

}
