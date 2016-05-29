<?php

namespace LumengPHP\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
use LumengPHP\DependencyInjection\ServiceContainer;

/**
 * 命令行应用
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Application extends SymfonyApplication {

    /**
     * @var AppContextInterface AppContext实例
     */
    private $appContext;

    public function __construct($configFilePath) {
        parent::__construct();

        $appConfig = new AppConfig(require($configFilePath));
        $container = new ServiceContainer(array());
        $this->appContext = new AppContext($appConfig, $container);
    }

    /**
     * 返回AppContext实例
     * @return AppContextInterface
     */
    public function getAppContext() {
        return $this->appContext;
    }

}
