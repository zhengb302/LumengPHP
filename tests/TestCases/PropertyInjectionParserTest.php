<?php

namespace tests\TestCases;

use LumengPHP\DependencyInjection\PropertyInjection\PropertyInjectionParser;

/**
 * 属性注入解析程序测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjectionParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string 
     */
    private $varDir;

    /**
     * @var string 
     */
    private $dumpPath;

    public function setUp() {
        $this->varDir = TEST_ROOT . '/var';
        if (!is_dir($this->varDir)) {
            mkdir($this->varDir);
        }

        $this->dumpPath = "{$this->varDir}/property-injection-aware-command-dump.php";

        if (file_exists($this->dumpPath)) {
            unlink($this->dumpPath);
        }
    }

    public function testParse() {
        $class = 'tests\Commands\PropertyInjectionAwareCommand';
        $parser = new PropertyInjectionParser($class);
        $parser->parse();
        $parser->dump($this->dumpPath);

        $this->assertFileExists($this->dumpPath);

        $expectedMetadataList = require(TEST_ROOT . '/resources/property-injection-aware-command-dump.php');

        $metadataList = require($this->dumpPath);

        $this->assertEquals($expectedMetadataList, $metadataList);
    }

}
