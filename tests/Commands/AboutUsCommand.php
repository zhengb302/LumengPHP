<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Kernel\Response;

/**
 * 关于我们 命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AboutUsCommand extends Command {

    public function execute() {
        return new Response('We are an great company!');
    }

}
