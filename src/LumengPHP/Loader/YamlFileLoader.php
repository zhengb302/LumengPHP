<?php

namespace LumengPHP\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Yaml配置文件加载程序
 *
 * @author Lumeng <zhengb302@163.com>
 */
class YamlFileLoader extends FileLoader {

    public function load($resource, $type = null) {
        $path = $this->locator->locate($resource, null, true);
        return $configValues = Yaml::parse(file_get_contents($path));
    }

    public function supports($resource, $type = null) {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

}
