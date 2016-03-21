<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 背后偷偷搞小动作的贼，没有任何输出，以防被察觉
 *
 * @author Lumeng <zhengb302@163.com>
 */
class BackgroundThiefCommand implements Command {

    public function init(AppContext $appContext) {
        
    }

    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request) {
        return new Response();
    }

}
