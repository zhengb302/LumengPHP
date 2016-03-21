<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;
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
        $key = $request->query->get('key');
        if ($key != '123456') {
            return new Response('HTTP 403 Forbidden', 403);
        }
    }

}
