<?php

namespace LumengPHP\Console\Commands\Event;

use LumengPHP\Components\Queue\QueueInterface;
use LumengPHP\Kernel\Annotation\ClassMetadataLoader;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Event\EventManagerInterface;
use ReflectionClass;

/**
 * 事件监听命令
 * 
 * 要求安装并启用了<b>PCNTL</b>和<b>POSIX</b>扩展
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
     * @var EventManagerInterface 
     * @service
     */
    private $eventManager;

    public function execute() {
        $evtConfig = $this->appContext->getAppSetting()->getEventConfig();
        if (empty($evtConfig)) {
            return;
        }

        $queueServices = [];
        foreach (array_keys($evtConfig) as $evtName) {
            $refObj = new ReflectionClass($evtName);
            $metadataLoader = new ClassMetadataLoader($this->appContext, $refObj);
            $classMetadata = $metadataLoader->load();

            //如果不是队列化的异步事件
            if (!isset($classMetadata['queued'])) {
                continue;
            }

            $queueServiceName = $classMetadata['queued'] ?: 'defaultEventQueue';
            if (!in_array($queueServiceName, $queueServices)) {
                $queueServices[] = $queueServiceName;
            }
        }

        //如果没有(需要)队列化的异步事件
        if (empty($queueServices)) {
            return;
        }

        //当前进程转为守护进程
        $this->daemon();

        foreach ($queueServices as $queueServiceName) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                _throw('创建子进程失败');
            }
            //父进程
            else if ($pid) {
                $status = 0;
                pcntl_wait($status);
            }
            //子进程
            else {
                /* @var $queueService QueueInterface */
                $queueService = $this->appContext->getService($queueServiceName);
                while ($event = $queueService->dequeue()) {
                    $this->eventManager->trigger($event);
                }
            }
        }
    }

    /**
     * 转为守护进程
     */
    private function daemon() {
        
    }

}
