<?php

namespace LumengPHP\Test;

use LumengPHP\Kernel\AppConfig;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\AppContext;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\Extension\ExtensionInterface;
use LumengPHP\Kernel\Facade\Facade;
use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\Response;

/**
 * 测试工作室
 *
 * @author Lumeng <zhengb302@163.com>
 */
class TestStudio {

    /**
     * @var TestStudio 
     */
    private static $studio;

    public static function initialize($configFilePath) {
        self::$studio = new self($configFilePath);
    }

    /**
     * 返回测试用的AppContext实例
     * @return AppContextInterface
     */
    public static function getAppContext() {
        return self::$studio->appContext;
    }

    /**
     * 调用命令，并返回命令所产生的Response对象
     * @param string $command 命令全路径名称
     * @param array           $query      The GET parameters
     * @param array           $post    The POST parameters
     * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array           $cookies    The COOKIE parameters
     * @param array           $files      The FILES parameters
     * @param array           $server     The SERVER parameters
     * @return Response
     */
    public static function invokeCommand($command, array $query = array(), array $post = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array()) {
        $request = new Request($query, $post, $attributes, $cookies, $files, $server);

        $cmd = new $command();

        //注入AppContext和Request
        $cmd->setAppContext(self::$studio->appContext);
        $cmd->setRequest($request);

        $cmd->init();

        return $cmd->execute();
    }

    /**
     * @var AppConfig AppConfig对象
     */
    private $appConfig;

    /**
     * @var ServiceContainer 服务容器
     */
    private $container;

    /**
     * @var AppContextInterface 
     */
    private $appContext;

    public function __construct($configFilePath) {
        $this->appConfig = new AppConfig(require($configFilePath));

        $this->initServiceContainer();

        $this->appContext = new AppContext($this->appConfig, $this->container);

        $this->container->registerService('appContext', $this->appContext);

        Facade::setAppContext($this->appContext);

        //加载扩展
        $this->loadExtensions();
    }

    /**
     * 初始化服务容器
     */
    private function initServiceContainer() {
        $serviceConfigs = $this->appConfig->get('app.services');

        //服务配置要不不存在，要不就是个数组
        assert(is_array($serviceConfigs) || is_null($serviceConfigs));

        if (is_null($serviceConfigs)) {
            $serviceConfigs = array();
        }

        $this->container = new ServiceContainer($serviceConfigs);
    }

    /**
     * 加载扩展
     */
    private function loadExtensions() {
        $extensions = $this->appConfig->get('app.extensions');

        //扩展配置要不不存在，要不就是个数组
        assert(is_array($extensions) || is_null($extensions));

        if (empty($extensions)) {
            return;
        }

        foreach ($extensions as $extensionClass) {
            $extension = new $extensionClass();

            assert($extension instanceof ExtensionInterface);

            //注入AppContext和ServiceContainer
            $extension->setAppContext($this->appContext);
            $extension->setServiceContainer($this->container);

            //加载扩展
            $extension->load();
        }
    }

}
