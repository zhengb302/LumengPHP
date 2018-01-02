<?php

namespace tests\TestCases;

use LumengPHP\Kernel\DependencyInjection\ServiceContainer;
use PHPUnit_Framework_TestCase;
use tests\Services\Bar;
use tests\Services\Foo;
use tests\Services\FooBar;

/**
 * 服务容器的测试
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase {

    public function testNormal() {
        $configs = [
            'foo' => [
                'class' => Foo::class,
            ],
        ];
        $container = new ServiceContainer($configs);

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertEquals('foo', $foo->foo());
    }

    public function testServiceNotExists() {
        $configs = [
            'foo' => [
                'class' => Foo::class,
            ],
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertNull($bar);
    }

    public function testRegService() {
        $container = new ServiceContainer([]);
        $container->register('foo', new Foo());

        $this->assertTrue($container->has('foo'));

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * 测试回调
     */
    public function testCallback() {
        $configs = [
            'foo' => [
                'class' => Foo::class,
            ],
            'bar' => function($container) {
                $foo = $container->get('foo');
                return new Bar($foo);
            },
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertInstanceOf(Bar::class, $bar);
        $this->assertEquals('fooBar', $bar->fooBar());
    }

    /**
     * 测试不带参数的回调
     */
    public function testCallbackWithoutArgument() {
        $configs = [
            'foo' => function() {
                return new Foo();
            },
        ];
        $container = new ServiceContainer($configs);

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertEquals('foo', $foo->foo());
    }

    /**
     * 测试构造器参数及服务引用
     */
    public function testConstructorArgsAndServiceRef() {
        $configs = [
            'foo' => [
                'class' => Foo::class,
            ],
            'bar' => [
                'class' => Bar::class,
                'constructor-args' => ['@foo'],
            ],
        ];
        $container = new ServiceContainer($configs);

        $bar = $container->get('bar');
        $this->assertInstanceOf(Bar::class, $bar);
        $this->assertEquals('fooBar', $bar->fooBar());
    }

    /**
     * 测试无效的服务构造器参数
     * 
     * @expectedException \LumengPHP\Kernel\DependencyInjection\ServiceContainerException
     * @expectedExceptionMessage constructor-args or call argument must be array!
     */
    public function testInvalidConstructorArgs() {
        $configs = [
            'bar' => [
                'class' => Bar::class,
                'constructor-args' => '@foo',
            ],
        ];
        $container = new ServiceContainer($configs);
        $container->get('bar');
    }

    /**
     * 测试服务配置里的方法调用
     */
    public function testCalls() {
        $configs = [
            'foo' => [
                'class' => Foo::class,
            ],
            'bar' => [
                'class' => Bar::class,
                'constructor-args' => ['@foo'],
            ],
            'fooBar' => [
                'class' => FooBar::class,
                //服务配置里的方法调用
                'calls' => [
                    //方法名称 => 方法参数数组
                    'setFoo' => ['@foo'],
                    'setBar' => ['@bar'],
                ],
            ],
        ];
        $container = new ServiceContainer($configs);

        $fooBar = $container->get('fooBar');
        $this->assertInstanceOf(FooBar::class, $fooBar);
        $this->assertEquals('fooBar', $fooBar->fooBar());
    }

    public function testAnonymousFunctionConfig() {
        $configs = [
            'foo' => function() {
                return new Foo();
            },
        ];
        $container = new ServiceContainer($configs);

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertEquals('foo', $foo->foo());
    }

    /**
     * 测试调用register方法时，传入服务配置
     */
    public function testConfigRegister() {
        $configs = [];
        $container = new ServiceContainer($configs);
        $container->register('foo', [
            'class' => Foo::class,
        ]);

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertEquals('foo', $foo->foo());
    }

    /**
     * 测试调用register方法时，传入回调函数
     */
    public function testAnonymousFunctionRegister() {
        $configs = [];
        $container = new ServiceContainer($configs);
        $container->register('foo', function() {
            return new Foo();
        });

        $foo = $container->get('foo');
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertEquals('foo', $foo->foo());
    }

}
