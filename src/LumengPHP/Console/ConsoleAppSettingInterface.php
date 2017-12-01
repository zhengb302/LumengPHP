<?php

namespace LumengPHP\Console;

use LumengPHP\Kernel\AppSettingInterface;

/**
 * 控制台应用配置接口
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface ConsoleAppSettingInterface extends AppSettingInterface {

    /**
     * 返回命令配置
     * 
     * 配置格式：命令名称 => 命令类全限定名称
     * 
     * 示例：
     * <pre>
     * [
     *     'cache:clear' => \MyApp\Console\Commands\Cache\Clear::class,
     *     'user:create' => \MyApp\Console\Commands\User\Create::class,
     *     'user:delete' => \MyApp\Console\Commands\User\Delete::class,
     * ]
     * </pre>
     * 
     * @return array
     */
    public function getCmds();
}
