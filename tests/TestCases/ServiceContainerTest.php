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
        $this->assertEquals('foo', $foo->foo());
    }

    public function testServiceNotExists() {
        $configs = [
            'foo' => [
                'class' => \tests\Services\Foo::class,
            ],
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertNull($bar);
    }

    public function testRegService() {
        $container = new ServiceContainer([]);
        $container->register('foo', new \tests\Services\Foo());

        $this->assertTrue($container->has('foo'));

        $foo = $container->get('foo');
        $this->assertInstanceOf(\tests\Services\Foo::class, $foo);
    }

    /**
     * 测试回调
     */
    public function testCallback() {
        $configs = [
            'foo' => [
                'class' => \tests\Services\Foo::class,
            ],
            'bar' => function($container) {
                $foo = $container->get('foo');
                return new \tests\Services\Bar($foo);
            },
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertInstanceOf(\tests\Services\Bar::class, $bar);
        $this->assertEquals('fooBar', $bar->fooBar());
    }

    /**
     * 测试构造器参数及服务引用
     */
    public function testConstructorArgsAndServiceRef() {
        $configs = [
            'foo' => [
                'class' => \tests\Services\Foo::class,
            ],
            'bar' => [
                'class' => \tests\Services\Bar::class,
                'constructor-args' => ['@foo'],
            ],
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertInstanceOf(\tests\Services\Bar::class, $bar);
        $this->assertEquals('fooBar', $bar->fooBar());
    }

    /**
     * 测试无效的服务构造器参数
     * @expectedException \LumengPHP\Kernel\DependencyInjection\ServiceContainerException
     * @expectedExceptionMessage constructor-args must be array!
     */
    public function testInvalidConstructorArgs() {
        $configs = [
            'bar' => [
                'class' => \tests\Services\Bar::class,
                'constructor-args' => '@foo',
            ],
        ];
        $container = new ServiceContainer($configs);
        $container->get('bar');
    }

    /**
     * 测试XXX
     */
    public function testCalls() {
        $configs = [
            'foo' => [
                'class' => \tests\Services\Foo::class,
            ],
            'bar' => [
                'class' => \tests\Services\Bar::class,
                'constructor-args' => ['@foo'],
            ],
            'fooBar' => [
                'class' => \tests\Services\FooBar::class,
                'calls' => [
                    'setFoo' => ['@foo'],
                    'setBar' => ['@bar'],
                ],
            ],
        ];
        $container = new ServiceContainer($configs);

        $fooBar = $container->get('fooBar');
        $this->assertInstanceOf(\tests\Services\FooBar::class, $fooBar);
        $this->assertEquals('fooBar', $fooBar->fooBar());
    }

}
