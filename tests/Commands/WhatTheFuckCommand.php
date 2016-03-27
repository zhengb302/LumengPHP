<?php

namespace tests\Commands;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class WhatTheFuckCommand implements Command {

    /**
     * @var string
     */
    private $siteName;

    public function init(AppContext $appContext) {
        $this->siteName = $appContext->getParameter('siteName');
    }

    public function execute(Request $request) {
        return new JsonResponse(array(
            'uid' => $request->query->get('uid'),
            'username' => $request->request->get('username'),
            'siteName' => $this->siteName,
        ));
    }

}
