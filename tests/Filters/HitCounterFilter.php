<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;

/**
 * 点击数统计filter
 *
 * @author Lumeng <zhengb302@163.com>
 */
class HitCounterFilter extends Filter {

    public static $counter = 0;

    public function doFilter() {
        self::$counter++;
    }

}
