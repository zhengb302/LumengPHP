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
     * 
     * @param Request $request
     * @return string 控制器类的全限定名称
     */
    public function route(Request $request);

    /**
     * 返回“请求”的<b>PATH_INFO</b>。
     * 无论是经过重写的URL，还是PATH_INFO模式的URL，
     * 或者是一坨屎一样的、全是查询字符串且根据GET请求参数进行路由的URL，
     * 都需要计算出一个统一的标识来识别当前请求，然后以<b>PATH_INFO</b>的形式返回。
     * 
     * 【示例】
     * 重写过的URL：/user/login  -->  /user/login
     * PATH_INFO模式的URL：/index.php/user/login  -->  /user/login
     * 屎一般的URL：/index.php?c=user&a=login  -->  /user/login
     * 
     * @return string
     */
    public function getPathInfo();
}
