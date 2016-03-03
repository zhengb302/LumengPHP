<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppKernel implements HttpKernelInterface, TerminableInterface {

    /**
     * @var EventDispatcher 
     */
    private $dispatcher;

    /**
     * @var ControllerResolver 
     */
    private $resolver;

    /**
     * @var HttpKernel 
     */
    private $kernel;

    public function __construct() {
        $this->dispatcher = new EventDispatcher();
        $this->resolver = new ControllerResolver();
        $this->kernel = new HttpKernel($this->dispatcher, $this->resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) {
        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response) {
        return $this->kernel->terminate($request, $response);
    }

}
