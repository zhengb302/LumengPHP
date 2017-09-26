<?php

namespace LumengPHP\Http\Routing;

use Exception;
use LumengPHP\Http\Request;

/**
 * 默认请求路由器<br />
 * 以请求的PATH_INFO作为key，映射到相应的控制器，配置格式：PATH_INFO => 控制器的全限定类名称
 * 只支持重写之后的URL类型
 * 配置示例：
 * [
 *     '/home' => \SomeApp\Controllers\Home::class,
 *     '/foo/bar' => \SomeApp\Controllers\Foo\Bar::class,
 * ]
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class DefaultRouter extends AbstractRouter {

    /**
     * @var string 
     */
    private $pathInfo;

    public function route(Request $request) {
        $requestUri = $request->getRequestUri();
        $pathInfo = $this->extractPathInfo($requestUri);
        if (!isset($this->routingConfig[$pathInfo])) {
            throw new Exception('您请求的控制器不存在~');
        }

        $this->pathInfo = $pathInfo;

        $controllerClass = $this->routingConfig[$pathInfo];
        return $controllerClass;
    }

    /**
     * 从RequestUri提取PathInfo<br />
     * 如：
     *     /foo/bar => /foo/bar
     *     /foo/bar?id=10086 => /foo/bar
     * 
     * @param string $requestUri
     * @return string
     */
    private function extractPathInfo($requestUri) {
        $questionMarkPos = strpos($requestUri, '?');
        if ($questionMarkPos === false) {
            return $requestUri;
        }

        return substr($requestUri, 0, $questionMarkPos);
    }

    public function getPathInfo() {
        return $this->pathInfo;
    }

}
