<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Http\HttpAppSettingInterface;
use LumengPHP\Http\Request;
use Exception;

/**
 * 简单的请求路由器，只支持URL重写之后的URL类型
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleRouter extends AbstractRouter {

    public function route(Request $request) {
        $requestUri = $request->getRequestUri();
        $components = explode('/', trim($requestUri, '/'));
        if (empty($components)) {
            throw new Exception('URL地址错误~');
        }

        foreach ($components as $i => $component) {
            $components[$i] = ucfirst($component);
            $this->verifyComponentName($components[$i]);
        }

        /* @var $appSetting HttpAppSettingInterface */
        $appSetting = $this->appContext->getAppSetting();
        $parentNamespace = $appSetting->getControllerParentNamespace();
        $controllerClass = "{$parentNamespace}\\" . implode('\\', $components);
        if (!class_exists($controllerClass)) {
            throw new Exception('您请求的控制器不存在~');
        }

        return $controllerClass;
    }

    private function requestUriToComponents($requestUri) {
        
    }

    private function verifyComponentName($componentName) {
        //大写英文字母开头，后边跟着一个或多个英文字母
        if (!preg_match('/^[A-Z][A-Za-z]+$/', $componentName)) {
            throw new Exception('控制器名称非法→_→');
        }
    }

}
