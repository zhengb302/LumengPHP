<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Http\Request;
use Exception;

/**
 * 简单的请求路由器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleRouter implements RouterInterface {

    public function route(Request $request) {
        $controllerName = ucfirst($request->get['c']);
        $actionName = ucfirst($request->get['a']);
        
        
    }

    private function verifyComponentName($componentName) {
        //大写英文字母开头，后边跟着一个或多个英文字母
        if (!preg_match('/^[A-Z][A-Za-z]+$/', $componentName)) {
            throw new Exception('控制器名称非法→_→');
        }
    }
}
