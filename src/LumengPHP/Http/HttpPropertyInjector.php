<?php

namespace LumengPHP\Http;

use Exception;
use LumengPHP\Kernel\AbstractPropertyInjector;
use LumengPHP\Kernel\AppContextInterface;

/**
 * HTTP属性注入器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class HttpPropertyInjector extends AbstractPropertyInjector {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var Request 
     */
    private $requestObj;

    /**
     * @var array 
     */
    private $get;

    /**
     * @var array 
     */
    private $post;

    /**
     * @var array 
     */
    private $request;

    /**
     * @var SessionInterface 
     */
    private $session;

    public function __construct(AppContextInterface $appContext, Request $request) {
        $this->appContext = $appContext;

        $this->requestObj = $request;
        $this->get = $this->requestObj->get;
        $this->post = $this->requestObj->post;
        $this->request = $this->requestObj->request;
        $this->session = $this->requestObj->getSession();
    }

    protected function getRawValue($source, $paramName) {
        switch ($source) {
            case 'get':
                $rawValue = $this->get[$paramName];
                break;
            case 'post':
                $rawValue = $this->post[$paramName];
                break;
            case 'request':
                $rawValue = $this->request[$paramName];
                break;
            case 'session':
                $rawValue = $this->session[$paramName];
                break;
            case 'config':
                $rawValue = $this->appContext->getConfig($paramName);
                break;
            case 'service':
                $rawValue = $this->appContext->getService($paramName);
                break;
            default:
                throw new Exception("不支持的数据源：{$source}");
        }

        return $rawValue;
    }

}
