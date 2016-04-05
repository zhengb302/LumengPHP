<?php

namespace LumengPHP\DependencyInjection\PropertyInjection;

use LumengPHP\DependencyInjection\ContainerCollection;

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
     * @var PropertyInjectionAwareInterface 
     */
    private $propertyInjectionAware;

    /**
     * @var array 属性注入元数据
     */
    private $metadataList;

    public function __construct(ContainerCollection $containerCollection, PropertyInjectionAwareInterface $propertyInjectionAware, array $metadataList) {
        $this->containerCollection = $containerCollection;
        $this->propertyInjectionAware = $propertyInjectionAware;
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
            $this->propertyInjectionAware->setProperty($propertyName, $value);
        }
    }

}
