<?php

namespace LumengPHP\Http\Routing;

use Exception;
use LumengPHP\Http\Request;

/**
 * 简单的请求路由器<br />
 * 以“URI Path”作为key，映射到相应的控制器，配置格式：URI Path => 控制器的全限定类名称
 * 只支持重写之后的URL类型
 * 配置示例：
 * [
 *     '/home' => \SomeApp\Controllers\Home::class,
 *     '/foo/bar' => \SomeApp\Controllers\Foo\Bar::class,
 * ]
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class SimpleRouter extends AbstractRouter {

    public function route(Request $request) {
        $requestUri = $request->getRequestUri();
        $uriPath = $requestUri;
        if (!isset($this->routingConfig[$uriPath])) {
            throw new Exception('您请求的控制器不存在~');
        }

        $controllerClass = $this->routingConfig[$uriPath];
        return $controllerClass;
    }

}
