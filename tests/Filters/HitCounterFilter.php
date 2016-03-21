<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;
use LumengPHP\Kernel\AppContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 点击数统计filter
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HitCounterFilter implements Filter {

    public static $counter = 0;

    public function init(AppContext $appContext) {
        
    }

    public function doFilter(Request $request, Response $response = null) {
        self::$counter++;
    }

}
