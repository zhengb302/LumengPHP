<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Kernel\Response;

/**
 * 显示订单明细命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShowOrderCommand extends Command {

    public function execute() {
        $id = $this->request->attributes->get('id');
        $name = $this->request->query->get('name');
        return new Response("id is {$id}, name is {$name}");
    }

}
