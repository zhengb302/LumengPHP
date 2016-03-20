<?php

namespace LumengPHP\DependencyInjection;

/**
 * 依赖注入服务容器<br />
 * Usage:
 *   $configs = ...
 *   $serviceContainer = new ServiceContainer($configs);
 *   $logger = $serviceContainer->get('logger');
 *   $logger->log('some important information...');
 * 
 * @author Lumeng <zhengb302@163.com>
 */
class ServiceContainer {

    /**
     *
     * @var array 服务配置
     */
    private $configs;

    /**
     *
     * @var array 服务map，格式：service name => service instance
     */
    private $services;

    public function __construct(array $configs) {
        $this->configs = $configs;
        $this->services = array();
    }

    /**
     * 根据服务名称返回一个服务对象
     * @param string $serviceName 服务名称
     * @return mixed|null 一个服务对象。如果服务不存在，返回null
     */
    public function get($serviceName) {
        if (!isset($this->configs[$serviceName])) {
            return null;
        }

        if (!isset($this->services[$serviceName])) {
            $this->buildService($serviceName);
        }

        return $this->services[$serviceName];
    }

    private function buildService($serviceName) {
        $serviceConfig = $this->configs[$serviceName];
        $parsedArgs = isset($serviceConfig['arguments']) ?
                $this->parseArgs($serviceConfig['arguments']) : null;

        if (empty($parsedArgs)) {
            $serviceInstance = new $serviceConfig['class']();
        } else {
            $ref = new \ReflectionClass($serviceConfig['class']);
            $serviceInstance = $ref->newInstanceArgs($parsedArgs);
        }

        $this->services[$serviceName] = $serviceInstance;
    }

    /**
     * 解析参数列表
     * @param array $rawArgs 未经处理的参数列表，必须为数组。
     * @return null|array
     */
    private function parseArgs($rawArgs) {
        if (!is_array($rawArgs)) {
            trigger_error('service arguments must be array!', E_USER_ERROR);
        }

        if (empty($rawArgs)) {
            return null;
        }

        $args = array();
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
     *   <li>\@HelloKity  则实际传入的是字符串 @HelloKity</dd>
     *   <li>其他任何字符串  返回原参数</li>
     * </ul>
     * @return mixed 可能的返回值：服务对象、字符串,etc
     */
    private function parseArg($rawArg) {
        $rawArgLen = strlen($rawArg);

        if (!is_string($rawArg) || $rawArgLen <= 1) {
            return $rawArg;
        }

        //以 @ 开头，则表示传入一个服务对象
        //如 @bar，则表示传入一个名称为 bar 的服务对象
        if ($rawArg[0] == '@') {
            $serviceName = substr($rawArg, 1, $rawArgLen - 1);
            return $this->get($serviceName);
        }

        //以 \@ 开头，则表示传入一个以@开头的字符串，反斜杠作为转义符
        //如 \@HelloKity，则实际传入的是 @HelloKity
        if ($rawArg[0] == '\\' && $rawArg[1] == '@') {
            return substr($rawArg, 1, $rawArgLen - 1);
        }

        //其他字符串
        return $rawArg;
    }

    /**
     * 注册服务<br />
     * 如果服务容器中已经存在名称相同的服务，则会覆盖原来的服务对象
     * @param string $serviceName 服务名称
     * @param mixed $serviceInstance 服务对象
     */
    public function registerService($serviceName, $serviceInstance) {
        if (isset($this->services[$serviceName])) {
            $this->services[$serviceName] = null;
            unset($this->services[$serviceName]);
        }

        $this->services[$serviceName] = $serviceInstance;
    }

}
