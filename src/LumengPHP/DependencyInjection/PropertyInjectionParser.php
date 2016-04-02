<?php

namespace LumengPHP\DependencyInjection;

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
     * @var array "容器-属性名称"对
     */
    private $containerPropertyPairs;

    public function __construct($argument) {
        $this->reflection = new ReflectionClass($argument);
    }

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

    public function dump($target) {
        var_dump($this->containerPropertyPairs);
    }

}
