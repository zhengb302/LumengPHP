<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 显示订单明细命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShowOrderCommand implements Command {

    public function init(AppContext $appContext) {
        
    }

    public function execute(Request $request) {
        $id = $request->attributes->get('id');
        $name = $request->query->get('name');
        return new Response("id is {$id}, name is {$name}");
    }

}
