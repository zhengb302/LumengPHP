<?php

namespace tests\Services;

/**
 * FooBar
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class FooBar {

    /**
     * @var Foo
     */
    private $foo;

    /**
     * @var Bar
     */
    private $bar;

    public function setFoo(Foo $foo) {
        $this->foo = $foo;
    }

    public function setBar(Bar $bar) {
        $this->bar = $bar;
    }

    public function fooBar() {
        return $this->foo->foo() . ucfirst($this->bar->bar());
    }

}
