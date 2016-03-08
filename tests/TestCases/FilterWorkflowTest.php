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

    public function testPreFilterWithRouteLimit() {
        $kernel = new AppKernel(TEST_ROOT . '/config/config.php');

        //第一个请求，正确的key
        $request = Request::create('/product/?key=123456', 'GET');
        $response = $kernel->handle($request);
        //$response->send();
        $kernel->terminate($request, $response);
        //
        $productList = json_decode($response->getContent(), true);
        $this->assertCount(2, $productList);
        $this->assertEquals('耐克鞋子', $productList[0]['name']);

        //第二个请求，错误的key
        $requestTwice = Request::create('/order/showOrder/25/?key=password', 'GET');
        $responseTwice = $kernel->handle($requestTwice);
        //$response->send();
        $kernel->terminate($requestTwice, $responseTwice);
        //
        $this->assertEquals(403, $responseTwice->getStatusCode());

        //第三个请求，没带key，然而此路径(路由)不在user-auth过滤器的处理范围之内
        $requestYetAnother = Request::create('/', 'GET');
        $responseYetAnother = $kernel->handle($requestYetAnother);
        //$response->send();
        $kernel->terminate($requestYetAnother, $responseYetAnother);
        //
        $this->assertEquals('defaultLocale: zh. homepage', $responseYetAnother->getContent());
    }

}
