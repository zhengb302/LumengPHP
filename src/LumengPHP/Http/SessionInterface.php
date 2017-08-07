<?php

namespace LumengPHP\Http;

use ArrayAccess;

/**
 * Session接口
 * 
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface SessionInterface extends ArrayAccess {

    public function clear();
}
