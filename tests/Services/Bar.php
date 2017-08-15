<?php

namespace tests\Services;

/**
 * Bar服务
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Bar {

    /**
     * @var Foo
     */
    private $foo;

    public function __construct(Foo $foo) {
        $this->foo = $foo;
    }

    public function fooBar() {
        return $this->foo->foo() . 'Bar';
    }

}
