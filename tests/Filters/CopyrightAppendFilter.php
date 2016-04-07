<?php

namespace tests\Filters;

use LumengPHP\Kernel\Filter\Filter;

/**
 * 版权信息附加过滤器<br />
 * 会在响应输出内容附件版权信息
 *
 * @author Lumeng <zhengb302@163.com>
 */
class CopyrightAppendFilter extends Filter {

    public function doFilter() {
        $content = $this->response->getContent();
        $this->response->setContent($content . "\nCopyright@2016");
    }

}
