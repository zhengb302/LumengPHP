<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\Bootstrap;

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

        $appContext = $bootstrap->getAppContext();
        $router = $appContext->getService('httpRouter');
        $this->dispatcher = new Dispatcher($appContext, $router);
    }

    public function handle(Request $request) {
        $this->dispatcher->doDispatcher($request);
    }

}