<?php

namespace LumengPHP\Http;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;

/**
 * HTTP应用<br />
 * Usage:
 *     $request = Request::createFromGlobals();
 * 
 *     $appSetting = new FooAppSetting();
 *     $configFilePath = '...somewhere...';
 *     $app = new Application($appSetting, $configFilePath);
 *     $app->handle($request);
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

    public function __construct(HttpAppSettingInterface $appSetting, $configFilePath) {
        $httpAppSetting = new HttpAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($httpAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();

        $this->buildDispatcher();
    }

    private function buildDispatcher() {
        $router = $this->appContext->getService('httpRouter');
        $resultHandler = $this->appContext->getService('httpResultHandler');
        $this->dispatcher = new Dispatcher($this->appContext, $router, $resultHandler);
    }

    public function handle(Request $request) {
        //把Request实例注册为服务
        $this->appContext->getServiceContainer()->register('request', $request);

        //执行派发动作
        $this->dispatcher->doDispatcher($request);
    }

}
