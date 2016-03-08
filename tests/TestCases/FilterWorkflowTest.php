<?php

namespace tests\TestCases;

use Symfony\Component\HttpFoundation\Request;
use LumengPHP\Kernel\AppKernel;
use tests\Filters\HitCounterFilter;

/**
 * 过滤器流程测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FilterWorkflowTest extends \PHPUnit_Framework_TestCase {

    public function testPreFilter() {
        $kernel = new AppKernel(TEST_ROOT . '/config/config.php');

        //第一个请求
        $request = Request::create('/backgroundThief', 'GET');
        $response = $kernel->handle($request);
        //$response->send();
        $kernel->terminate($request, $response);

        //第二个请求
        $requestTwice = Request::create('/backgroundThief', 'GET');
        $responseTwice = $kernel->handle($requestTwice);
        //$response->send();
        $kernel->terminate($requestTwice, $responseTwice);

        //第三个请求
        $requestYetAnother = Request::create('/', 'GET');
        $responseYetAnother = $kernel->handle($requestYetAnother);
        //$response->send();
        $kernel->terminate($requestYetAnother, $responseYetAnother);

        //点击数此时应该为3
        $this->assertEquals(3, HitCounterFilter::$counter);
    }

}
