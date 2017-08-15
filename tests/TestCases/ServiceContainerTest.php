<?php

namespace tests\TestCases;

use PHPUnit_Framework_TestCase;
use LumengPHP\Kernel\DependencyInjection\ServiceContainer;

/**
 * 服务容器的测试
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase {

    public function testNormal() {
        $configs = [
            'foo' => [
                'class' => \tests\Services\Foo::class,
            ],
        ];
        $container = new ServiceContainer($configs);

        $foo = $container->get('foo');
        $this->assertInstanceOf(\tests\Services\Foo::class, $foo);
        $this->assertEquals('foo!', $foo->foo());
    }

}
