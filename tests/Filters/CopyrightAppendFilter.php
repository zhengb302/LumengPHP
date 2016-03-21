<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;
use LumengPHP\Kernel\AppContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 版权信息附加过滤器<br />
 * 会在响应输出内容附件版权信息
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CopyrightAppendFilter implements Filter {

    public function init(AppContext $appContext) {
        
    }

    public function doFilter(Request $request, Response $response = null) {
        $content = $response->getContent();
        $response->setContent($content . "\nCopyright@2016");
    }

}
