<?php

namespace tests\Commands;

use Symfony\Component\HttpFoundation\Response;

/**
 * 首页命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HomepageCommand extends \LumengPHP\Command\CommandBase {

    public function init() {
        
    }

    public function execute() {
        return new Response('homepage');
    }

}
