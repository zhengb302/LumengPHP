<?php

namespace VendorName\ProjectName\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Kernel\Response;

/**
 * 首页 命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HomeCommand extends Command {

    public function init() {
        
    }

    /**
     * 命令执行入口
     * @return Response
     */
    public function execute() {
        return new Response('hello world!');
    }

}
