<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use LumengPHP\Kernel\Command\CommandInterface;
use InvalidArgumentException;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ControllerResolver implements ControllerResolverInterface {

    /**
     * @var AppContext AppContext实例
     */
    private $appContext;

    public function __construct(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function getController(BaseRequest $request) {
        $cmdClass = $request->attributes->get('_cmd');

        $cmd = new $cmdClass();
        if (!($cmd instanceof CommandInterface)) {
            $msg = "{$cmdClass} isn't instance of LumengPHP\Command\CommandInterface.";
            throw new InvalidArgumentException($msg);
        }

        //注入AppContext和Request
        $cmd->setAppContext($this->appContext);
        $cmd->setRequest($request);

        $cmd->init();

        $callable = array($cmd, 'execute');
        return $callable;
    }

    public function getArguments(BaseRequest $request, $controller) {
        return array();
    }

}
