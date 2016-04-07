<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;
use LumengPHP\Kernel\Response;

/**
 * 用户权限检查filter
 *
 * @author Lumeng <zhengb302@163.com>
 */
class UserAuthFilter extends Filter {

    public function doFilter() {
        $key = $this->request->query->get('key');
        if ($key != '123456') {
            return new Response('HTTP 403 Forbidden', 403);
        }
    }

}
