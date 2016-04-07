<?php

namespace tests\TestCases;

use LumengPHP\Test\TestStudio;

/**
 * TestStudio测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class TestStudioTest extends \PHPUnit_Framework_TestCase {

    public function testNormal() {
        //此句用在测试环境的bootstrap中
        TestStudio::initialize(TEST_ROOT . '/config/config.php');

        //这段代码纯粹只是为了测试
        $appContext = TestStudio::getAppContext();
        $this->assertNotNull($appContext);
        $this->assertInstanceOf('LumengPHP\Kernel\AppContext', $appContext);

        //测试用例中通常都是像下面这个样子
        $command = 'tests\Commands\WhatTheFuckCommand';
        $response = TestStudio::invokeCommand($command, array('uid' => 3), array('username' => 'linda'));
        $jsonContent = $response->getJsonContent();
        $this->assertNotNull($jsonContent);

        $expectedResult = array(
            'uid' => 3,
            'username' => 'linda',
            'siteName' => 'LumengPHP!',
        );
        $this->assertEquals($expectedResult, $jsonContent);
    }

}
