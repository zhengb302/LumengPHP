<?php

namespace tests\TestCases;

use LumengPHP\Kernel\Request;
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

    public function testPostFilter() {
        $kernel = new AppKernel(TEST_ROOT . '/config/config.php');

        //第一个请求，copyright-appender过滤器有作用到此路径上，响应内容有被修改
        $request = Request::create('/about-us', 'GET');
        $response = $kernel->handle($request);
        //$response->send();
        $kernel->terminate($request, $response);

        $this->assertEquals("We are an great company!\nCopyright@2016", $response->getContent());

        //第二个请求，copyright-appender过滤器未作用到此路径上，所以响应内容未被修改。
        $requestTwice = Request::create('/order/showOrder/49/?name=zhangsan&key=123456', 'GET');
        $responseTwice = $kernel->handle($requestTwice);
        //$response->send();
        $kernel->terminate($requestTwice, $responseTwice);

        $this->assertEquals("id is 49, name is zhangsan", $responseTwice->getContent());
    }

}
