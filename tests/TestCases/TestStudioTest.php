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

        //测试用例中通常都是像下面这个样子
        $command = 'tests\Commands\WhatTheFuckCommand';

        $query = array('uid' => 3);
        $post = array('username' => 'linda', 'user_age' => 18);

        $response = TestStudio::invokeCommand($command, $query, $post);

        $jsonContent = $response->getJsonContent();
        $this->assertNotNull($jsonContent);

        $expectedResult = array(
            'uid' => 3,
            'username' => 'linda',
            'age' => 18,
            'sex' => 1,
            'siteName' => 'LumengPHP!',
        );
        $this->assertEquals($expectedResult, $jsonContent);
    }

}
