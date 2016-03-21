<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 显示产品列表命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ShowProductListCommand implements Command {

    public function init(AppContext $appContext) {
        
    }

    /**
     * 
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request) {
        $data = array(
            array('name' => '耐克鞋子', 'inventory' => '50'),
            array('name' => '三叶草T恤', 'inventory' => '28'),
        );
        return new JsonResponse($data);
    }

}
