<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 首页命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HomepageCommand implements Command {

    /**
     * @var string 
     */
    private $defaultLocale;

    public function init(AppContext $appContext) {
        $this->defaultLocale = $appContext->getConfig('framework.defaultLocale');
    }

    public function execute(Request $request) {
        return new Response("defaultLocale: {$this->defaultLocale}. homepage");
    }

}
