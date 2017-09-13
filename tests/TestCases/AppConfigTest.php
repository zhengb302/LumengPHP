<?php

namespace tests\TestCases;

use PHPUnit_Framework_TestCase;
use LumengPHP\Kernel\AppConfig;

/**
 * AppConfig的测试
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class AppConfigTest extends PHPUnit_Framework_TestCase {

    public function testNormal() {
        $config = [
            'foo' => 'bar',
        ];
        $appConfig = new AppConfig($config);

        $value = $appConfig->get('foo');
        $this->assertEquals('bar', $value);
    }

    public function testKeyNotExists() {
        $config = [
            'foo' => 'bar',
        ];
        $appConfig = new AppConfig($config);

        $value = $appConfig->get('fuck');
        $this->assertNull($value);
    }

}
