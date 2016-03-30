<?php

namespace tests\TestCases;

use LumengPHP\DependencyInjection\PropertyInjectionParser;

/**
 * 属性注入解析程序测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjectionParserTest extends \PHPUnit_Framework_TestCase {

    public function testParse() {
        $class = 'tests\Commands\PropertyInjectionAwareCommand';
        $parser = new PropertyInjectionParser($class);
        $parser->parse();
        $parser->dump('');
    }

}
