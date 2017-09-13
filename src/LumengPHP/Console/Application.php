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

    /**
     * @var array 命令映射
     */
    private $cmdMapping;

    public function __construct(ConsoleAppSettingInterface $appSetting, $configFilePath) {
        $this->consoleAppSetting = new ConsoleAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($this->consoleAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();

        $this->cmdMapping = $this->consoleAppSetting->getCmdMapping();
    }

    public function run() {
        $opts = getopt('li:h', ['list', 'info:', 'help']);
        if ($opts) {
            $this->processOpts($opts);
            return;
        }

        $argc = $_SERVER['argc'];
        $argv = $_SERVER['argv'];
        if ($argc < 2) {
            echo "参数错误~\n";
            $this->pringUsage();
            exit(-1);
        }

        $cmdName = $argv[1];
        if (!isset($this->cmdMapping[$cmdName])) {
            echo "命令“{$cmdName}”不存在~\n";
            exit(-1);
        }

        $cmdClass = $this->cmdMapping[$cmdName];

        $propertyInjector = new ConsolePropertyInjector($this->appContext);
        $classInvoker = new ClassInvoker($this->appContext, $propertyInjector);

        try {
            $classInvoker->invoke($cmdClass);
        } catch (Exception $ex) {
            echo "发生异常，异常消息：", $ex->getMessage(), "\n";
            exit(-1);
        }
    }

    private function processOpts($opts) {
        foreach ($opts as $opt => $value) {
            switch ($opt) {
                case 'l':
                case 'list':
                    $this->listAllCmds();
                    break;
                case 'i':
                case 'info':
                    $cmdName = $value;
                    $this->showCmd($cmdName);
                    break;
                case 'h':
                case 'help':
                    $this->pringUsage();
                    break;
            }

            //这些选项都是互斥的，处理了一个，就不再处理其他选项
            break;
        }
    }

    /**
     * 列出所有命令
     */
    private function listAllCmds() {
        if (empty($this->cmdMapping)) {
            echo "您尚未定义任何命令~\n";
            return;
        }

        $cmdNameArr = array_keys($this->cmdMapping);
        echo implode("\n", $cmdNameArr), "\n";
    }

    /**
     * 显示命令信息
     * 
     * @param string $cmdName 命令名称
     */
    private function showCmd($cmdName) {
        if (!isset($this->cmdMapping[$cmdName])) {
            echo "命令“{$cmdName}”不存在~\n";
            return;
        }

        $cmdClass = $this->cmdMapping[$cmdName];
        $refObj = new \ReflectionClass($cmdClass);
        $docComment = $refObj->getDocComment();
        $firstLineComment = $this->extractFirstLine($docComment);

        echo "命令名称：{$cmdName}\n";
        echo "类名称：{$cmdClass}\n";
        echo "说明：{$firstLineComment}\n";
    }

    private function extractFirstLine($docComment) {
        $tmpDocComment = str_replace("\r\n", "\n", $docComment);
        $commentLines = explode("\n", $tmpDocComment);
        foreach ($commentLines as $line) {
            $comment = strip_tags(trim($line, "/* \t"));
            if ($comment) {
                return $comment;
            }
        }

        return '';
    }

    private function pringUsage() {
        echo "Usage:\n";
        echo "    launch <cmd name> [arg1] [arg2] ... [argX]\n";
        echo "    launch -l\n";
        echo "Options:\n";
        echo "    -l, --list    列出所有命令\n";
        echo "    -i, --info <cmd name>    显示命令信息\n";
        echo "    -h, --help    显示此帮助\n";
        echo "    \n";
    }

}
