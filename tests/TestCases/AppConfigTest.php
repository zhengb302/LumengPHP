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

    public function testDotSyntax() {
        $config = [
            'foo' => 'bar',
            'parameters' => [
                'siteName' => 'LumengPHP',
                'pageSize' => 10,
            ],
        ];
        $appConfig = new AppConfig($config);

        $pageSize = $appConfig->get('parameters.pageSize');
        $this->assertEquals(10, $pageSize);
    }

    public function testMultiDot() {
        $config = [
            'foo' => 'bar',
            'parameters' => [
                'siteName' => 'LumengPHP',
                'size' => [
                    'pageSize' => 10,
                    'bulkSize' => 250,
                ],
            ],
        ];
        $appConfig = new AppConfig($config);

        $bulkSize = $appConfig->get('parameters.size.bulkSize');
        $this->assertEquals(250, $bulkSize);
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
