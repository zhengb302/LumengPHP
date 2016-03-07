<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
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

    /**
     * @var AppConfig 
     */
    private $appConfig;

    public function init(AppContext $appContext) {
        $this->appConfig = $appContext->getAppConfig();
        $this->defaultLocale = $this->appConfig->get('framework.defaultLocale');
    }

    public function execute(Request $request) {
        return new Response("defaultLocale: {$this->defaultLocale}. homepage");
    }

}
