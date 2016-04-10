<?php

namespace LumengPHP\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use LumengPHP\Kernel\AppContext;

/**
 * 命令行命令
 *
 * @author Lumeng <zhengb302@163.com>
 */
class Command extends SymfonyCommand {

    /**
     * 
     * @return Application
     */
    public function getApplication() {
        return parent::getApplication();
    }

    /**
     * 返回AppContext实例
     * @return AppContext
     */
    protected function getAppContext() {
        return $this->getApplication()->getAppContext();
    }

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
