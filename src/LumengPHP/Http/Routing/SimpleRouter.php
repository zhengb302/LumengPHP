<?php

namespace LumengPHP\Http\Routing;

use Exception;
use LumengPHP\Http\Request;

/**
 * 简单的请求路由器<br />
 * 以“URI Path”作为key，映射到相应的控制器，配置格式：URI Path => 控制器的全限定类名称
 * 支持重写之后的URL类型或PathInfo类型的URL
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
        //PathInfo类型的URL，例如“/index.php/foo/bar”，PathInfo就是“/foo/bar”
        $pathInfo = $request->getPathInfo();
        if ($pathInfo) {
            $uriPath = $pathInfo;
        }
        //重写之后的URL类型，例如“/foo/bar”、“/foo/bar?id=10086”
        else {
            $requestUri = $request->getRequestUri();
            $uriPath = $this->extractUriPath($requestUri);
        }

        if (!isset($this->routingConfig[$uriPath])) {
            throw new Exception('您请求的控制器不存在~');
        }

        $controllerClass = $this->routingConfig[$uriPath];
        return $controllerClass;
    }

    /**
     * 从RequestUri提取Uri Path<br />
     * 如：
     *     /foo/bar => /foo/bar
     *     /foo/bar?id=10086 => /foo/bar
     * 
     * @param string $requestUri
     * @return string
     */
    private function extractUriPath($requestUri) {
        $questionMarkPos = strpos($requestUri, '?');
        if ($questionMarkPos === false) {
            return $requestUri;
        }

        return substr($requestUri, 0, $questionMarkPos);
    }

}
