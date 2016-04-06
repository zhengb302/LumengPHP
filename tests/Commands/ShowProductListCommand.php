<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Misc\JsonResponse;

/**
 * 显示产品列表命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShowProductListCommand extends Command {

    public function execute() {
        $data = array(
            array('name' => '耐克鞋子', 'inventory' => '50'),
            array('name' => '三叶草T恤', 'inventory' => '28'),
        );
        return new JsonResponse($data);
    }

}
