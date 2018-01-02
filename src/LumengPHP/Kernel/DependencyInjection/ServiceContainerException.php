<?php

namespace LumengPHP\Kernel\DependencyInjection;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * 与服务容器相关的异常
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ServiceContainerException extends Exception implements ContainerExceptionInterface {
    
}
