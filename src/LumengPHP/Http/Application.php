<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;
use LumengPHP\Http\Routing\SimpleRouter;
use LumengPHP\Http\Result\SimpleResultHandler;

/**
 * HTTP应用
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Application {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct($configFilePath) {
        $bootstrap = new Bootstrap();
        $bootstrap->boot($configFilePath);

        $this->appContext = $bootstrap->getAppContext();

        $this->init();
    }

    private function init() {
        $router = $this->appContext->getService('httpRouter');
        if (is_null($router)) {
            $router = new SimpleRouter($this->appContext);
            $this->appContext->getServiceContainer()->register('httpRouter', $router);
        }

        $resultHandler = $this->appContext->getService('httpResultHandler');
        if (is_null($resultHandler)) {
            $resultHandler = new SimpleResultHandler();
            $this->appContext->getServiceContainer()->register('httpResultHandler', $router);
        }

        $this->dispatcher = new Dispatcher($this->appContext, $router, $resultHandler);
    }

    public function handle(Request $request) {
        $this->dispatcher->doDispatcher($request);
    }

}
