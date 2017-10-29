<?php

namespace LumengPHP\Console;

use Exception;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Bootstrap;
use LumengPHP\Kernel\ClassInvoker;
use LumengPHP\Kernel\Event\EventManager;
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
     * @var array 命令映射
     */
    private $cmdMapping;

    /**
     * @var string 启动器名称
     */
    private $launcherName = 'console';

    /**
     * @var bool 如果有选项，就退出运行
     */
    private $exitWhenHasOpts = true;

    /**
     * @var bool 是否是详细信息模式
     */
    private $isVerbose = false;

    public function __construct(ConsoleAppSettingInterface $appSetting, $configFilePath) {
        $consoleAppSetting = new ConsoleAppSetting($appSetting);

        $bootstrap = new Bootstrap();
        $bootstrap->boot($consoleAppSetting, $configFilePath);

        $this->appContext = $bootstrap->getAppContext();

        $this->cmdMapping = $consoleAppSetting->getCmdMapping();
    }

    /**
     * 设置启动器名称<br />
     * 启动器名称用于打印帮助消息的时候显示，默认的名称为“console”。
     * 如果应用使用了新的控制台名称，可以调用此方法修改，及时更新能避免用户产生疑惑。
     * 
     * @param string $launcherName
     */
    public function setLauncherName($launcherName) {
        $this->launcherName = $launcherName;
    }

    public function run() {
        $opts = getopt('lvh', ['list', 'verbose', 'help']);
        if ($opts) {
            $this->processOpts($opts);
            if ($this->exitWhenHasOpts) {
                return;
            }
        }

        $this->runCmd();
    }

    private function processOpts($opts) {
        foreach ($opts as $opt => $value) {
            switch ($opt) {
                case 'l':
                case 'list':
                    $this->listAllCmds();
                    break;
                case 'v':
                case 'verbose':
                    $this->exitWhenHasOpts = false;
                    $this->isVerbose = true;
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

    /**
     * 打印帮助信息
     */
    private function pringUsage() {
        $usage = "\n";
        $usage .= "Usage:\n";
        $usage .= "    {$this->launcherName} <cmd name> [arg 1] [arg 2] ... [arg n]\n";
        $usage .= "    {$this->launcherName} -v <cmd name> [arg 1] [arg 2] ... [arg n]\n";
        $usage .= "    {$this->launcherName} -l\n";
        $usage .= "    {$this->launcherName} -h\n\n";
        $usage .= "Options:\n";
        $usage .= "    -l, --list       列出所有命令\n";
        $usage .= "    -v, --verbose    尽可能多的显示输出信息，在调试的时候非常有用\n";
        $usage .= "    -h, --help       显示此帮助\n";
        $usage .= "    \n";

        echo $usage;
    }

    private function runCmd() {
        list($argc, $argv) = $this->prepareCmdLineArgs();

        $cmdName = $argv[1];
        if (!isset($this->cmdMapping[$cmdName])) {
            echo "命令“{$cmdName}”不存在~\n";
            exit(-1);
        }

        $container = $this->appContext->getServiceContainer();

        //构造“InputInterface”实例，并注册为名为“input”服务，以供程序中读取命令行参数
        $args = array_slice($argv, 1, $argc - 1);
        $input = new Input($args);
        $container->register('input', $input);

        //构造 ClassInvoker 对象，并把其注册为服务
        $propertyInjector = new ConsolePropertyInjector($this->appContext);
        $classInvoker = new ClassInvoker($this->appContext, $propertyInjector);
        $container->register('classInvoker', $classInvoker);

        //构造事件管理器，请把其注册为服务
        $eventConfig = $this->appContext->getAppSetting()->getEventConfig();
        $eventManager = new EventManager($eventConfig, $this->appContext, $classInvoker);
        $container->register('eventManager', $eventManager);

        try {
            $cmdClass = $this->cmdMapping[$cmdName];
            $classInvoker->invoke($cmdClass);
        } catch (Exception $ex) {
            echo "发生异常，异常消息：", $ex->getMessage(), "\n";
            if ($this->isVerbose) {
                echo "异常跟踪信息：\n", $ex->getTraceAsString(), "\n";
            }
            exit(-1);
        }
    }

    private function prepareCmdLineArgs() {
        $argc = $_SERVER['argc'];
        $argv = $_SERVER['argv'];

        //如果是“详细信息模式”，去掉中间的“-v”参数，
        //保证 $argv 的第1个元素就是命令名称（下标从0开始）
        if ($this->isVerbose) {
            $argc = $argc - 1;
            array_splice($argv, 1, 1);
        }

        if ($argc < 2) {
            echo "参数错误~\n";
            $this->pringUsage();
            exit(-1);
        }

        return [$argc, $argv];
    }

}
