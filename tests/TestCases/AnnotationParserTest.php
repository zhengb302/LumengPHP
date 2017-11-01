<?php

namespace tests\TestCases;

use LumengPHP\Kernel\Annotation\Metadata;
use LumengPHP\Kernel\Annotation\Parser\Lexer;
use LumengPHP\Kernel\Annotation\Parser\Parser;
use PHPUnit_Framework_TestCase;

/**
 * 注解语法分析程序测试用例
 *
 * @author Lumeng <zhengb302@163.com>
 */
class AnnotationParserTest extends PHPUnit_Framework_TestCase {

    public function testParseQueued() {
        $docComment = "/**
                        * 某事件
                        *
                        * @queued(fuckingQueue)
                        * @author Lumeng <zhengb302@163.com>
                        */";
        $metadata = new Metadata();
        $parser = new Parser(new Lexer($docComment), $metadata);
        $parser->parse();

        $allMetadata = $metadata->getAllMetadata();
        $this->assertEquals('fuckingQueue', $allMetadata['queued']);
    }

}
