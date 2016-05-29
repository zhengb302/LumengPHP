<?php

namespace LumengPHP\Test;

use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\Response;
use LumengPHP\Kernel\Bootstrap;

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
     * @var AppContextInterface 
     */
    private $appContext;

    public function __construct($configFilePath) {
        $bootstrap = new Bootstrap();
        $bootstrap->boot($configFilePath);

        $this->appContext = $bootstrap->getAppContext();
    }

}
