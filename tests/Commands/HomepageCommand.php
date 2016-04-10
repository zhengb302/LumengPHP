<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Kernel\Response;

/**
 * 首页命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HomepageCommand extends Command {

    /**
     * @var string 
     */
    private $defaultLocale;

    public function init() {
        $this->defaultLocale = $this->appContext->getConfig('app.defaultLocale');
    }

    public function execute() {
        return new Response("defaultLocale: {$this->defaultLocale}. homepage");
    }

}
