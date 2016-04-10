<?php

namespace LumengPHP\Console\Commands;

use LumengPHP\Console\Command;

/**
 * 用于创建"类"的命令 基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class ClassCreateCommand extends Command {

    protected function getAppRootDir() {
        return $this->getAppContext()->getRootDir();
    }

    protected function getAppSetting() {
        $settingFile = $this->getAppRootDir() . '/app.setting.json';

        $settingContent = file_get_contents($settingFile);
        return json_decode($settingContent, true);
    }

    protected function getNamespaceRoot() {
        $appSetting = $this->getAppSetting();
        return $appSetting['namespace-root'];
    }

    protected function getNamespaceRootDir() {
        $appSetting = $this->getAppSetting();
        return $appSetting['namespace-root-dir'];
    }

    protected function getStubDir() {
        return __DIR__ . '/stubs';
    }

}
