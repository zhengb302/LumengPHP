<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 关于我们 命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AboutUsCommand implements Command {

    public function init(AppContext $appContext) {
        
    }

    public function execute(Request $request) {
        return new Response('We are an great company!');
    }

}
