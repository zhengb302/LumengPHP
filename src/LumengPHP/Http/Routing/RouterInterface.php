<?php

namespace LumengPHP\Http\Routing;

use LumengPHP\Http\Request;

/**
 * <b>请求路由器</b>接口<br />
 * 一个“请求路由器”会把一个“请求”路由到一个“控制器”，并确保这个控制器在系统中存在
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface RouterInterface {

    /**
     * 把一个“请求”路由到一个“控制器”，返回控制器类的全限定名称
     * @param Request $request
     * @return string 控制器类的全限定名称
     */
    public function route(Request $request);

    /**
     * 返回“请求”的翻译过的pathinfo
     * 
     * @return string
     */
    public function getTranslatedPathInfo();
}
