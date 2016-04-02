<?php

namespace LumengPHP\DependencyInjection;

/**
 * 属性注射器
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjector {

    /**
     * @var PropertyInjectionAwareInterface 
     */
    private $propertyInjectionAware;

    /**
     *
     * @var array 属性注入元数据
     */
    private $metadataList;

    public function __construct(PropertyInjectionAwareInterface $propertyInjectionAware, array $metadataList) {
        $this->propertyInjectionAware = $propertyInjectionAware;
        $this->metadataList = $metadataList;
    }

    public function doInject() {
        foreach ($this->metadataList as $metadata) {
            $containerName = $metadata['container'];
            $container = '';
            $value = $container->get($metadata['key']);

            $propertyName = $metadata['property'];
            $this->propertyInjectionAware->setProperty($propertyName, $value);
        }
    }

}
