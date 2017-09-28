<?php

namespace LumengPHP\Console;

use Exception;
use LumengPHP\Kernel\AbstractPropertyInjector;
use LumengPHP\Kernel\AppContextInterface;

/**
 * 控制台属性注入器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ConsolePropertyInjector extends AbstractPropertyInjector {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

    protected function getRawValue($source, $paramName) {
        switch ($source) {
            case 'config':
                $rawValue = $this->appContext->getConfig($paramName);
                break;
            case 'service':
                $rawValue = $this->appContext->getService($paramName);
                break;
            case 'currentEvent':
                $rawValue = $this->appContext->getService('eventManager')->getCurrentEvent();
                break;
            default:
                throw new Exception("不支持的数据源：{$source}");
        }

        return $rawValue;
    }

}
