<?php

namespace LumengPHP\Console;

use Exception;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;
use LumengPHP\Kernel\ClassInvoker;

/**
 * 控制台应用
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Application {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var ConsoleAppSettingInterface 
     */
    private $consoleAppSetting;

    public function __construct(ConsoleAppSettingInterface $appSetting, $configFilePath) {
        $this->consoleAppSetting = new ConsoleAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($this->consoleAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();
    }

    public function run() {
        $argc = $_SERVER['argc'];
        $argv = $_SERVER['argv'];
        if ($argc < 2) {
            echo "参数错误~\n";
            echo "Usage:\n";
            echo "    launch <cmd name> [arg1] [arg2] ... [argX]";
            exit(-1);
        }

        $cmdName = $argv[1];
        $cmdMapping = $this->consoleAppSetting->getCmdMapping();
        if (!isset($cmdMapping[$cmdName])) {
            echo "命令“{$cmdName}”不存在~\n";
            exit(-1);
        }

        $cmdClass = $cmdMapping[$cmdName];

        $propertyInjector = new ConsolePropertyInjector($this->appContext);
        $classInvoker = new ClassInvoker($this->appContext, $propertyInjector);

        try {
            $classInvoker->invoke($cmdClass);
        } catch (Exception $ex) {
            echo "发生异常，异常消息：", $ex->getMessage(), "\n";
            exit(-1);
        }
    }

}
