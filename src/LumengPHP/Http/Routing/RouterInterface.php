<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Http\Request;

/**
 * <b>请求路由器</b>接口<br />
 * 一个“请求路由器”会把一个“请求”路由到一个“控制器”
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface RouterInterface {

    /**
     * 把一个“请求”路由到一个“控制器”，返回路由的结果
     * @param Request $request
     * @return array 路由结果数据
     */
    public function route(Request $request);
}
