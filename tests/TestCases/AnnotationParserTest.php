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

    /**
     * 测试“@queued”注解
     */
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

    /**
     * 测试“@queued”注解未携带参数的情况
     * 
     * “@queued”注解的参数是可选的，如果参数不存在，则注解的值为<b>true</b>
     */
    public function testParseQueuedWithoutParam() {
        $docComment = "/**
                        * 某事件
                        *
                        * @queued
                        * @author Lumeng <zhengb302@163.com>
                        */";
        $metadata = new Metadata();
        $parser = new Parser(new Lexer($docComment), $metadata);
        $parser->parse();

        $allMetadata = $metadata->getAllMetadata();
        $this->assertTrue($allMetadata['queued']);
    }

    /**
     * 测试“@queue”注解
     */
    public function testParseQueue() {
        $docComment = "/**
                        * 某Job
                        *
                        * @queue(fuckingQueue)
                        * @author Lumeng <zhengb302@163.com>
                        */";
        $metadata = new Metadata();
        $parser = new Parser(new Lexer($docComment), $metadata);
        $parser->parse();

        $allMetadata = $metadata->getAllMetadata();
        $this->assertEquals('fuckingQueue', $allMetadata['queue']);
    }

    /**
     * 测试“@queue”注解未携带参数的情况
     * 
     * “@queue”注解的参数是必填的
     * 
     * @expectedException \Exception
     * @expectedExceptionMessage 语法错误
     */
    public function testParseQueueWithoutParam() {
        $docComment = "/**
                        * 某Job
                        *
                        * @queue
                        * @author Lumeng <zhengb302@163.com>
                        */";
        $metadata = new Metadata();
        $parser = new Parser(new Lexer($docComment), $metadata);
        $parser->parse();
    }

}
