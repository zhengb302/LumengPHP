<?php

namespace LumengPHP\DependencyInjection\PropertyInjection;

use LumengPHP\DependencyInjection\ContainerCollection;
use ReflectionObject;

/**
 * 属性注射器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjector {

    /**
     * @var ContainerCollection 
     */
    private $containerCollection;

    /**
     * @var mixed 
     */
    private $object;

    /**
     * @var ReflectionObject 
     */
    private $objectReflection;

    /**
     * @var array 属性注入元数据
     */
    private $metadataList;

    public function __construct(ContainerCollection $containerCollection, $object, array $metadataList) {
        $this->containerCollection = $containerCollection;
        $this->object = $object;
        $this->objectReflection = new ReflectionObject($object);
        $this->metadataList = $metadataList;
    }

    public function doInject() {
        foreach ($this->metadataList as $metadata) {
            $containerName = $metadata['container'];
            $container = $this->containerCollection->get($containerName);

            $key = $metadata['key'];
            if (!$container->has($key)) {
                continue;
            }

            $value = $container->get($key);

            $propertyName = $metadata['property'];
            $property = $this->objectReflection->getProperty($propertyName);
            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }

            $property->setValue($this->object, $value);
        }
    }

}
