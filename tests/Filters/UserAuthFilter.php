<?php

namespace tests\Filters;

use LumengPHP\Filter\Filter;
use LumengPHP\Kernel\AppContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 用户权限检查filter
 *
 * @author Lumeng <zhengb302@163.com>
 */
class UserAuthFilter implements Filter {

    public function init(AppContext $appContext) {
        
    }

    public function doFilter(Request $request, Response $response = null) {
        
    }

}
