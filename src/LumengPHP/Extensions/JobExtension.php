<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\AbstractExtension;
use LumengPHP\Job\JobDispatcher;
use LumengPHP\Exceptions\InvalidConfigurationException;

/**
 * job扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobExtension extends AbstractExtension {

    public function getName() {
        return 'LumengPHP-Job';
    }

    public function load() {
        $jobConfig = $this->appContext->getConfig('job');

        //如果job配置为空，抛出异常
        if (empty($jobConfig)) {
            throw new InvalidConfigurationException('missing job config.');
        }

        //把job转发器注册为服务
        $this->container->registerService('jobDispatcher', function($container) {
            //获取消息连接管理器
            $messagingConnManager = $container->get('messagingConnManager');

            //获取job配置
            $appContext = $container->get('appContext');
            $jobConfig = $appContext->getConfig('job');

            return new JobDispatcher($messagingConnManager, $jobConfig);
        });
    }

}
