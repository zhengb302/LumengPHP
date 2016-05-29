<?php

namespace tests\Commands;

use LumengPHP\Kernel\Command\Command;
use LumengPHP\Misc\JsonResponse;

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

    /**
     * @var int 
     */
    private $age;

    /**
     * @var int 性别：1，男；0，女
     */
    private $sex;

    public function init() {
        $this->siteName = $this->appContext->getParameter('siteName');
        $this->age = $this->request->request->get('user_age');
        $this->sex = $this->request->request->get('sex', 1);
    }

    public function execute() {
        return new JsonResponse(array(
            'uid' => $this->request->query->get('uid'),
            'username' => $this->request->request->get('username'),
            'age' => $this->age,
            'sex' => $this->sex,
            'siteName' => $this->siteName,
        ));
    }

}
