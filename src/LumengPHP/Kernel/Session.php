<?php

namespace LumengPHP\Kernel;

/**
 * 会话接口
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
interface Session extends \ArrayAccess {

    /**
     * 设置当前用户
     * @param array $currentUser 当前用户数据
     */
    public function setCurrentUser($currentUser);

    /**
     * 返回当前用户
     * @return array|null 当前用户数据。如果未登录，则返回null
     */
    public function getCurrentUser();
}
