<?php

namespace LumengPHP\DependencyInjection\PropertyInjection;

use ReflectionClass;
use ReflectionProperty;

/**
 * 属性注入解析程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjectionParser {

    /**
     * @var ReflectionClass 
     */
    private $reflection;

    /**
     * @var array "属性-容器-key"对
     */
    private $containerPropertyPairs = array();

    /**
     * 构造一个属性注入解析器
     * @param mixed $argument (全限定的)类名，或者一个对象
     */
    public function __construct($argument) {
        $this->reflection = new ReflectionClass($argument);
    }

    /**
     * 解析并生成结果，之后可以调用getResult方法返回结果，
     * 或者调用dump方法导出结果
     */
    public function parse() {
        $properties = $this->reflection->getProperties();
        foreach ($properties as $property) {
            $this->parsePropertyDocComment($property);
        }
    }

    private function parsePropertyDocComment(ReflectionProperty $property) {
        $docComment = $property->getDocComment();

        $pattern = '#@from\(([a-zA-Z][a-zA-Z0-9_]*)(\[([a-zA-Z][a-zA-Z0-9_]*)\])?\)#';
        $matches = array();
        preg_match($pattern, $docComment, $matches);
        if (empty($matches)) {
            return;
        }

        $propertyName = $property->getName();
        $containerName = $matches[1];
        $key = isset($matches[3]) ? $matches[3] : $propertyName;

        $this->containerPropertyPairs[] = array(
            'property' => $propertyName,
            'container' => $containerName,
            'key' => $key,
        );
    }

    /**
     * 返回解析结果(元数据)
     * @return array
     */
    public function getResult() {
        return $this->containerPropertyPairs;
    }

    /**
     * 把解析结果dump到$target参数所指定的文件中
     * @param string $target 目标文件路径
     */
    public function dump($target) {
        $export = var_export($this->containerPropertyPairs, true);
        $content = "<?php\nreturn {$export};";
        file_put_contents($target, $content);
    }

}
