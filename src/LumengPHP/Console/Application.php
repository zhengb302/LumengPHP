<?php

namespace LumengPHP\Console;

use Exception;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;
use LumengPHP\Kernel\ClassInvoker;
use ReflectionClass;

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

        $cmdInfos = "\n";
        foreach ($this->cmdMapping as $cmdName => $cmdClass) {
            $cmdInfos = $cmdInfos . $this->buildCmdInfo($cmdName, $cmdClass);
        }

        echo $cmdInfos;
    }

    /**
     * 显示命令信息
     * 
     * @param string $cmdName 命令名称
     */
    private function buildCmdInfo($cmdName, $cmdClass) {
        $refObj = new ReflectionClass($cmdClass);
        $docComment = $refObj->getDocComment();
        $firstLineComment = $this->extractFirstLine($docComment);

        return str_pad($cmdName, 25, ' ') . $firstLineComment . "\n";
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
        $usage = "\n";
        $usage .= "Usage:\n";
        $usage .= "    launch <cmd name> [arg1] [arg2] ... [argX]\n";
        $usage .= "    launch -l\n\n";
        $usage .= "Options:\n";
        $usage .= "    -l, --list    列出所有命令\n";
        $usage .= "    -h, --help    显示此帮助\n";
        $usage .= "    \n";

        echo $usage;
    }

}
