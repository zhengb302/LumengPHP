<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use LumengPHP\Command\Command;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ControllerResolver implements ControllerResolverInterface {

    /**
     * @var AppContext
     */
    private $appContext;

    /**
     * @var AppConfig
     */
    private $appConfig;

    public function __construct(AppContext $appContext, AppConfig $appConfig) {
        $this->appContext = $appContext;
        $this->appConfig = $appConfig;
    }

    public function getController(Request $request) {
        $cmdClass = $request->attributes->get('_cmd');

        //@todo 这里可能引入CommandBuilder，而不是直接实例化
        $cmdInstance = new $cmdClass($this->appContext, $this->appConfig, $request);
        if (!($cmdInstance instanceof Command)) {
            $msg = "{$cmdClass} isn't sub-class of LumengPHP\Command\Command.";
            throw new \InvalidArgumentException($msg);
        }

        $callable = array($cmdInstance, 'execute');
        return $callable;
    }

    public function getArguments(Request $request, $controller) {
        return array();
    }

}
