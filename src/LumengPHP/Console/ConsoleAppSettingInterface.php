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
     * 返回命令映射
     * @return array 关联数组，格式：命令名称 => 命令类全限定名称
     */
    public function getCmdMapping();
}
