<?php

namespace LumengPHP\Kernel\DependencyInjection;

use Closure;
use ReflectionClass;
use ReflectionFunction;

/**
 * 服务对象实例构造器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ServiceBuilder {

    /**
     * @var ContainerInterface 服务容器
     */
    private $serviceContainer;

    public function __construct(ContainerInterface $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * 构造服务对象实例
     * @param array|callable $serviceConfig 服务配置
     * @return mixed 服务对象实例
     */
    public function build($serviceConfig) {
        //“服务配置”可以是一个匿名函数
        if ($serviceConfig instanceof Closure) {
            $callback = $serviceConfig;
            $refFunc = new ReflectionFunction($callback);
            return $refFunc->getNumberOfParameters() == 1 ? $callback($this->serviceContainer) : $callback();
        }

        /*
         * 以下是数组形式的服务配置
         */

        $refClass = new ReflectionClass($serviceConfig['class']);

        //实例化
        $constructorArgs = isset($serviceConfig['constructor-args']) ? $serviceConfig['constructor-args'] : [];
        $parsedArgs = $this->parseArgs($constructorArgs);
        $serviceInstance = $refClass->newInstanceArgs($parsedArgs);

        //如果需要调用实例方法
        if (isset($serviceConfig['calls'])) {
            foreach ($serviceConfig['calls'] as $methodName => $methodArgs) {
                $refMethod = $refClass->getMethod($methodName);
                $refMethod->invokeArgs($serviceInstance, $this->parseArgs($methodArgs));
            }
        }

        return $serviceInstance;
    }

    /**
     * 解析参数列表
     * @param array $rawArgs 未经处理的参数列表，必须为数组。
     * @return null|array
     */
    private function parseArgs($rawArgs) {
        if (!is_array($rawArgs)) {
            throw new ServiceContainerException('constructor-args or call argument must be array!');
        }

        if (empty($rawArgs)) {
            return [];
        }

        $args = [];
        foreach ($rawArgs as $rawArg) {
            $args[] = $this->parseArg($rawArg);
        }
        return $args;
    }

    /**
     * 解析单个参数
     * @param mixed $rawArg <br/>
     * 参数示例：
     * <ul>
     *   <li>0、null、对象、长度小于或等于1的字符串,etc  返回原参数</li>
     *   <li>@bar  则表示传入一个名称为 bar 的服务对象</li>
     *   <li>\@bar  则实际传入的是字符串 @bar</dd>
     *   <li>其他任何字符串  返回原参数</li>
     * </ul>
     * @return mixed 可能的返回值：服务对象、字符串、其他类型的值,etc
     */
    private function parseArg($rawArg) {
        //如果不是字符串，则传入原值
        if (!is_string($rawArg)) {
            return $rawArg;
        }

        $rawArgLen = strlen($rawArg);
        if ($rawArgLen <= 1) {
            return $rawArg;
        }

        //以 @ 开头，则表示引用一个服务对象。例如 @bar，表示引用一个名称为 bar 的服务对象
        if ($rawArg[0] == '@') {
            $serviceName = substr($rawArg, 1, $rawArgLen - 1);
            return $this->serviceContainer->get($serviceName);
        }

        //以 \@ 开头，则表示传入一个以@开头的字符串，反斜杠作为转义符
        //例如 \@bar，则实际传入的是 @bar
        if ($rawArg[0] == '\\' && $rawArg[1] == '@') {
            return substr($rawArg, 1, $rawArgLen - 1);
        }

        //其他字符串
        return $rawArg;
    }

}
