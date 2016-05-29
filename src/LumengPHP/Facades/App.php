<?php

namespace LumengPHP\Facades;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Facade\Facade;

/**
 * facade of AppContext
 *
 * @see AppContextInterface
 * @author Lumeng <zhengb302@163.com>
 */
class App extends Facade {

    protected static function getServiceName() {
        return 'appContext';
    }

}
