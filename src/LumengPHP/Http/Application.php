<?php

namespace LumengPHP\Http;

/**
 * HTTP应用
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Application {

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct($configFilePath) {
        $bootstrap = new Bootstrap();
        $bootstrap->boot($configFilePath);

        $this->dispatcher = new Dispatcher();
    }

    public function handle() {
        $this->dispatcher->doDispatcher($controllerName, $actionName);
    }

}
