<?php

namespace tests\Commands;

use LumengPHP\Command\AbstractCommand;
use Symfony\Component\HttpFoundation\Response;

/**
 * 首页命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HomepageCommand extends AbstractCommand {

    public function init() {
        
    }

    public function execute() {
        return new Response('homepage');
    }

}
