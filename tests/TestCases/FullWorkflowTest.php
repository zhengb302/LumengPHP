<?php

namespace tests\TestCases;

use LumengPHP\Kernel\Request;
use LumengPHP\Kernel\AppKernel;

/**
 * 全流程测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FullWorkflowTest extends \PHPUnit_Framework_TestCase {

    public function testFullWorkflow() {
        $request = Request::create('/', 'GET');

        $kernel = new AppKernel(TEST_ROOT . '/config/config_http.php');

        $response = $kernel->handle($request);

        $this->expectOutputString('defaultLocale: zh. homepage');
        $response->send();

        $kernel->terminate($request, $response);
    }

    public function testPathWithPlaceholder() {
        $request = Request::create('/order/showOrder/38/?name=lilei&key=123456', 'GET');

        $kernel = new AppKernel(TEST_ROOT . '/config/config_http.php');

        $response = $kernel->handle($request);

        $this->expectOutputString('id is 38, name is lilei');
        $response->send();

        $kernel->terminate($request, $response);
    }

}
