<?php

namespace LumengPHP\Kernel\Annotation;

use ReflectionClass;
use LumengPHP\Kernel\Annotation\Parser\Lexer;
use LumengPHP\Kernel\Annotation\Parser\Parser;

/**
 * 类注解导出
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ClassAnnotationDumper {

    /**
     * @var ReflectionClass 要被导出注解的类的反射对象
     */
    private $reflectionObj;

    /**
     * @var array 属性注解元数据，属性名称作为key
     */
    private $propertyAnnotationMetaData = [];

    /**
     * @var array 方法注解元数据，方法名称作为key
     */
    private $methodAnnotationMetaData = [];

    public function __construct(ReflectionClass $reflectionObj) {
        $this->reflectionObj = $reflectionObj;
    }

    /**
     * 导出并返回类的注解
     * @param string $path 导出文件路径
     * @return array 类的注解
     */
    public function dump($path) {
        $this->parsePropertyAnnotation();
        $this->parseMethodAnnotation();

        $classAnnotation = [
            'propertyAnnotationMetaData' => $this->propertyAnnotationMetaData,
            'methodAnnotationMetaData' => $this->methodAnnotationMetaData,
        ];

        file_put_contents($path, "<?php\nreturn " . var_export($classAnnotation, true) . ";\n");

        return $classAnnotation;
    }

    /**
     * 解析类的<b>属性</b>注解
     */
    private function parsePropertyAnnotation() {
        $properties = $this->reflectionObj->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();

            $metaData = new Metadata();

            $docComment = $property->getDocComment();
            $parser = new Parser(new Lexer($docComment), $metaData);
            $parser->parse();

            if (!isset($metaData['source'])) {
                continue;
            }

            $this->propertyAnnotationMetaData[$propertyName] = $metaData->getAllMetadata();
        }
    }

    /**
     * 解析类的<b>方法</b>注解
     */
    private function parseMethodAnnotation() {
        $methods = $this->reflectionObj->getMethods();
        foreach ($methods as $method) {
            $methodName = $method->getName();

            $metaData = new Metadata();

            $docComment = $method->getDocComment();
            $parser = new Parser(new Lexer($docComment), $metaData);
            $parser->parse();

            $metaDataArray = $metaData->getAllMetadata();
            if (!empty($metaDataArray)) {
                $this->methodAnnotationMetaData[$methodName] = $metaDataArray;
            }
        }
    }

}
