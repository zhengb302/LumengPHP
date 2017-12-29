<?php

namespace LumengPHP\Http;

use LumengPHP\Http\Events\HttpEnd;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;
use LumengPHP\Kernel\ClassInvoker;
use LumengPHP\Kernel\Event\EventManager;
use LumengPHP\Kernel\Job\JobManager;

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

    public function __construct(HttpAppSettingInterface $appSetting, $configFilePath) {
        $httpAppSetting = new HttpAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($httpAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();
    }

    public function handle(Request $request) {
        $container = $this->appContext->getServiceContainer();

        //把Request实例注册为服务
        $container->register('request', $request);

        //构造 ClassInvoker 对象，并把其注册为服务
        $propertyInjector = new HttpPropertyInjector($this->appContext, $request);
        $classInvoker = new ClassInvoker($this->appContext, $propertyInjector);
        $container->register('classInvoker', $classInvoker);

        //构造Job管理器，并把其注册为服务
        $jobManager = new JobManager($this->appContext);
        $container->register('jobManager', $jobManager);

        //构造事件管理器，请把其注册为服务
        $eventManager = new EventManager($this->appContext, $classInvoker);
        $container->register('eventManager', $eventManager);

        //构造请求派发器
        $router = $container->get('httpRouter');
        $resultHandler = $container->get('httpResultHandler');
        $dispatcher = new Dispatcher($this->appContext, $router, $resultHandler);

        //执行派发动作
        $dispatcher->dispatch($request);

        //触发HTTP应用执行完毕事件
        $eventManager->trigger(new HttpEnd());
    }

}
