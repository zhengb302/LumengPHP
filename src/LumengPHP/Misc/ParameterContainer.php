<?php

namespace LumengPHP\Misc;

use LumengPHP\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 参数容器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ParameterContainer implements ContainerInterface {

    /**
     * @var ParameterBag 
     */
    private $parameterBag;

    public function __construct(ParameterBag $parameterBag) {
        $this->parameterBag = $parameterBag;
    }

    public function get($key) {
        return $this->parameterBag->get($key);
    }

    public function has($key) {
        return $this->parameterBag->has($key);
    }

}
