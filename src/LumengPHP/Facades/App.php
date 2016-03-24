<?php

namespace LumengPHP\Facades;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\Facade\Facade;

/**
 * facade of AppContext
 *
 * @see AppContext
 * @author Lumeng <zhengb302@163.com>
 */
class App extends Facade {

    protected static function getServiceName() {
        return 'appContext';
    }

}
