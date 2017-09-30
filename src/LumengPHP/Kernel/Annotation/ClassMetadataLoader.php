<?php

namespace LumengPHP\Kernel\Annotation;

use LumengPHP\Kernel\AppContextInterface;
use ReflectionClass;

/**
 * 类元数据导出程序
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ClassMetadataLoader {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var ReflectionClass 要被导出元数据的类的反射对象
     */
    private $reflectionObj;

    public function __construct(AppContextInterface $appContext, ReflectionClass $reflectionObj) {
        $this->appContext = $appContext;
        $this->reflectionObj = $reflectionObj;
    }

    /**
     * 加载并返回类的元数据
     * 
     * @return array 类的元数据
     */
    public function load() {
        $metadataCacheDir = $this->appContext->getRuntimeDir() . '/cache/class-metadata';
        if (!is_dir($metadataCacheDir)) {
            mkdir($metadataCacheDir, 0755, true);
        }

        //inode、最后修改时间
        $classFilePath = $this->reflectionObj->getFileName();
        $inode = fileinode($classFilePath);
        $lastModifiedTime = filemtime($classFilePath);

        //类的全限定名称
        $classFullName = $this->reflectionObj->getName();

        //类元数据缓存文件名及路径
        $cacheFileName = str_replace('\\', '.', $classFullName) . ".{$inode}.{$lastModifiedTime}.php";
        $cacheFilePath = $metadataCacheDir . '/' . $cacheFileName;

        if (is_file($cacheFilePath)) {
            $classMetadata = require($cacheFilePath);
        } else {
            $classAnnotationDumper = new ClassAnnotationDumper($this->reflectionObj);
            $classMetadata = $classAnnotationDumper->dump($cacheFilePath);
        }

        return $classMetadata;
    }

}
