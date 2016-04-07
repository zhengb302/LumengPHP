<?php

namespace LumengPHP\Kernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use LumengPHP\Kernel\Command\CommandInterface;
use LumengPHP\DependencyInjection\PropertyInjection\PropertyInjectionParser;
use LumengPHP\DependencyInjection\PropertyInjection\PropertyInjector;
use LumengPHP\DependencyInjection\ContainerCollection;

/**
 * 
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ControllerResolver implements ControllerResolverInterface {

    /**
     * @var AppContext AppContext实例
     */
    private $appContext;

    /**
     * @var ContainerCollection 容器集合
     */
    private $containerCollection;

    public function __construct(AppContext $appContext, ContainerCollection $containerCollection) {
        $this->appContext = $appContext;
        $this->containerCollection = $containerCollection;
    }

    public function getController(BaseRequest $request) {
        $cmdClass = $request->attributes->get('_cmd');

        $cmd = new $cmdClass();
        if (!($cmd instanceof CommandInterface)) {
            $msg = "{$cmdClass} isn't instance of LumengPHP\Command\CommandInterface.";
            throw new \InvalidArgumentException($msg);
        }

        //注入AppContext和Request
        $cmd->setAppContext($this->appContext);
        $cmd->setRequest($request);

        //注入属性
        $this->injectProperty($cmdClass, $cmd);

        $cmd->init();

        $callable = array($cmd, 'execute');
        return $callable;
    }

    /**
     * 向cmd中注入属性
     * @param string $cmdClass 全限定的cmd类名
     * @param CommandInterface $cmd
     */
    private function injectProperty($cmdClass, CommandInterface $cmd) {
        //元数据缓存目录
        $cacheDir = $this->appContext->getCacheDir() . '/property-injection';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        //元数据文件名：把全限定的类名中的反斜杠，改为横杠
        $metadataFilename = str_replace('\\', '-', ltrim($cmdClass, '\\')) . '.php';

        $injectionMetadataFile = "{$cacheDir}/{$metadataFilename}";
        if (!file_exists($injectionMetadataFile)) {
            $parser = new PropertyInjectionParser($cmd);
            $parser->parse();
            $parser->dump($injectionMetadataFile);
        }

        $injectionMetadataList = require($injectionMetadataFile);
        if (empty($injectionMetadataList)) {
            return;
        }

        $injector = new PropertyInjector($this->containerCollection, $cmd, $injectionMetadataList);
        $injector->doInject();
    }

    public function getArguments(BaseRequest $request, $controller) {
        return array();
    }

}
