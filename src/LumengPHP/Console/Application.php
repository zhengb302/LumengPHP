<?php

namespace LumengPHP\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;

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

        $bootstrap = new Bootstrap();
        $bootstrap->boot($configFilePath);

        $this->appContext = $bootstrap->getAppContext();
    }

    /**
     * 返回AppContext实例
     * @return AppContextInterface
     */
    public function getAppContext() {
        return $this->appContext;
    }

}
