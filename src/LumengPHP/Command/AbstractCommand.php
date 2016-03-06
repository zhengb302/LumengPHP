<?php

namespace LumengPHP\Command;

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * Command抽象基类，实现了一些通用的方法
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class AbstractCommand implements Command {

    /**
     * @var AppContext 
     */
    private $appContext;

    /**
     * @var AppConfig 
     */
    private $appConfig;

    /**
     * @var Request 
     */
    private $request;

    public function __construct(AppContext $appContext, AppConfig $appConfig, Request $request) {
        $this->appContext = $appContext;
        $this->appConfig = $appConfig;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppContext() {
        return $this->appContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppConfig() {
        return $this->appConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest() {
        return $this->request;
    }

}
