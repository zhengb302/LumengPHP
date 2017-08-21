<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppSettingInterface;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;

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

    public function __construct(AppSettingInterface $appSetting, $configFilePath) {
        $httpAppSetting = new HttpAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($httpAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();

        $this->init();
    }

    private function init() {
        $router = $this->appContext->getService('httpRouter');
        $resultHandler = $this->appContext->getService('httpResultHandler');
        $this->dispatcher = new Dispatcher($this->appContext, $router, $resultHandler);
    }

    public function handle(Request $request) {
        $this->dispatcher->doDispatcher($request);
    }

}
