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

    public function getController(Request $request) {
        $cmdClass = $request->attributes->get('_cmd');

        $cmdInstance = new $cmdClass();
        if (!($cmdInstance instanceof Command)) {
            $msg = "{$cmdClass} isn't sub-class of LumengPHP\Command\Command.";
            throw new \InvalidArgumentException($msg);
        }

        $callable = array($cmdInstance, 'execute');
        return $callable;
    }

    public function getArguments(Request $request, $controller) {
        return array($request);
    }

}
