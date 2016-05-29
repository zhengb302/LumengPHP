<?php

namespace LumengPHP\Extensions;

use LumengPHP\Kernel\Extension\Extension;

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
        $messagingConfig = $this->appContext->getConfig('messaging');

        //如果消息服务配置为空，则表示不需要消息服务，退出
        if (empty($messagingConfig)) {
            return;
        }
    }

}
