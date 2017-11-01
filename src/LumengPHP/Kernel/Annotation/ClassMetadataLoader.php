<?php

namespace LumengPHP\Kernel\Annotation;

use LumengPHP\Kernel\AppContextInterface;
use ReflectionClass;

/**
 * 类元数据加载程序
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class ClassMetadataLoader {

    /**
     * @var AppContextInterface
     */
    private $appContext;

    /**
     * @var array 元数据缓存Map，格式：类全限定名称 => 类元数据
     */
    private $metadataMap = [];

    public function __construct(AppContextInterface $appContext) {
        $this->appContext = $appContext;
    }

    /**
     * 加载并返回类的元数据
     * 
     * @param string $className 类全限定名称
     * @return array 类元数据
     */
    public function load($className) {
        //如果内存缓存里有
        if (isset($this->metadataMap[$className])) {
            return $this->metadataMap[$className];
        }

        //如果没有就加载
        $classMetadata = $this->doLoad($className);

        //存入内存缓存
        $this->metadataMap[$className] = $classMetadata;

        return $classMetadata;
    }

    private function doLoad($className) {
        $classRefObj = new ReflectionClass($className);

        //准备文件缓存目录
        $metadataCacheDir = $this->appContext->getRuntimeDir() . '/cache/class-metadata';
        if (!is_dir($metadataCacheDir)) {
            mkdir($metadataCacheDir, 0755, true);
        }

        //inode、最后修改时间
        $classFilePath = $classRefObj->getFileName();
        $inode = fileinode($classFilePath);
        $lastModifiedTime = filemtime($classFilePath);

        //类元数据缓存文件名及路径
        $cacheFileName = str_replace('\\', '.', $className) . ".{$inode}.{$lastModifiedTime}.php";
        $cacheFilePath = $metadataCacheDir . '/' . $cacheFileName;

        if (is_file($cacheFilePath)) {
            $classMetadata = require($cacheFilePath);
        } else {
            $classAnnotationDumper = new ClassAnnotationDumper($classRefObj);
            $classMetadata = $classAnnotationDumper->dump($cacheFilePath);
        }

        return $classMetadata;
    }

}
