<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class WhatTheFuckCommand extends Command {

    /**
     * @var string
     */
    private $siteName;

    public function init() {
        $this->siteName = $this->appContext->getParameter('siteName');
    }

    public function execute() {
        return new JsonResponse(array(
            'uid' => $this->request->query->get('uid'),
            'username' => $this->request->request->get('username'),
            'siteName' => $this->siteName,
        ));
    }

}
