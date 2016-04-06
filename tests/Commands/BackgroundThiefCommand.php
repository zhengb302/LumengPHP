<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Kernel\Response;

/**
 * 背后偷偷搞小动作的贼，没有任何输出，以防被察觉
 *
 * @author Lumeng <zhengb302@163.com>
 */
class BackgroundThiefCommand extends Command {

    public function execute() {
        return new Response();
    }

}
