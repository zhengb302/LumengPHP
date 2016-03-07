<?php

namespace tests\TestCases;

use Symfony\Component\HttpFoundation\Request;
use LumengPHP\Kernel\AppKernel;

/**
 * 全流程测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class FullWorkflowTest extends \PHPUnit_Framework_TestCase {

    public function testFullWorkflow() {
        $request = Request::create('/', 'GET');

        $kernel = new AppKernel(TEST_ROOT . '/config/config.php');

        $response = $kernel->handle($request);

        $this->expectOutputString('defaultLocale: zh. homepage');
        $response->send();

        $kernel->terminate($request, $response);
    }

}
