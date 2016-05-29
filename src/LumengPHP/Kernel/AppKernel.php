<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * AppKernel convert a Request object to a Response one.
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AppKernel implements HttpKernelInterface, TerminableInterface {

    /**
     * @var HttpKernel 
     */
    private $kernel;

    public function __construct($configFilePath) {
        $bootstrap = new Bootstrap();
        $bootstrap->boot($configFilePath);

        $appContext = $bootstrap->getAppContext();
        $this->kernel = $appContext->getService('httpKernel');
    }

    /**
     * {@inheritdoc}
     */
    public function handle(SymfonyRequest $request, $type = self::MASTER_REQUEST, $catch = true) {
        //处理请求
        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(SymfonyRequest $request, Response $response) {
        return $this->kernel->terminate($request, $response);
    }

}
