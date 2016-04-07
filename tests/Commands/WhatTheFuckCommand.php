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
     * @from(request[user_age])
     */
    private $age;

    /**
     * 性别：1，男；0，女<br />
     * 这里可以用于测试当容器中不存在此key的时候，会使用(或者说，"保留")默认值
     * @var int
     * @from(request)
     */
    private $sex = 1;

    public function init() {
        $this->siteName = $this->appContext->getParameter('siteName');
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
