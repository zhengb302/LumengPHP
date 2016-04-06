<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use LumengPHP\Kernel\Command\CommandInterface;

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

    public function __construct(AppContext $appContext) {
        $this->appContext = $appContext;
    }

    public function getController(Request $request) {
        $cmdClass = $request->attributes->get('_cmd');

        $cmdInstance = new $cmdClass();
        if (!($cmdInstance instanceof CommandInterface)) {
            $msg = "{$cmdClass} isn't instance of LumengPHP\Command\CommandInterface.";
            throw new \InvalidArgumentException($msg);
        }

        $cmdInstance->setAppContext($this->appContext);
        $cmdInstance->setRequest($request);
        $cmdInstance->init();

        $callable = array($cmdInstance, 'execute');
        return $callable;
    }

    public function getArguments(Request $request, $controller) {
        return array();
    }

}
