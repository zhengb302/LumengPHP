<?php

namespace LumengPHP\Console\Commands\Event;

use LumengPHP\Console\ConsoleAppSettingInterface;
use LumengPHP\Kernel\AppContextInterface;

/**
 * 事件监听命令
 * 
 * 根据事件配置，会为每一个事件队列开启一个子进程，一个队列对应一个子进程，多个事件可以共享一个队列。
 * 子进程会监听队列里的事件数据，一旦有事件到达，便执行此事件的监听器，执行完此事件所有的监听器之后，
 * 子进程会继续监听事件数据，如果没有新的事件到达，要么阻塞(使用了阻塞型的队列)，要么退出(使用了非阻塞型的队列或阻塞超时)。
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Listen {

    /**
     * @var AppContextInterface
     * @service
     */
    private $appContext;

    /**
     * @var ConsoleAppSettingInterface 
     * @service
     */
    private $appSetting;

    public function execute() {
        $evtConfig = $this->appSetting->getEventConfig();
        if (empty($evtConfig)) {
            return;
        }

        foreach ($evtConfig as $evtName => $listeners) {
            
        }
    }

}
