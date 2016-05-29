<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\Extension;
use LumengPHP\Job\JobDispatcher;

/**
 * job扩展
 *
 * @author Lumeng <zhengb302@163.com>
 */
class JobExtension extends Extension {

    public function getName() {
        return 'LumengPHP-Job';
    }

    public function load() {
        $jobConfig = $this->appContext->getConfig('job');

        //如果job配置为空，则表示不需要job，退出
        if (empty($jobConfig)) {
            return;
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
