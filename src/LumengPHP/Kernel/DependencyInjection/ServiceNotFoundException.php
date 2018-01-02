<?php

namespace LumengPHP\Kernel\DependencyInjection;

use Psr\Container\NotFoundExceptionInterface;

/**
 * 服务不存在异常
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ServiceNotFoundException extends ServiceContainerException implements NotFoundExceptionInterface {
    
}
