<?php

namespace tests\TestCases;

use LumengPHP\Kernel\ConfigLoader;
use LumengPHP\Kernel\AppConfig;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\AppContext;
use LumengPHP\Extensions\DatabaseExtension;
use LumengPHP\Db\ConnectionManager;
use tests\Misc\DumpLogger;

/**
 * 数据库Model测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ConnectionManager 数据库连接管理器
     */
    private $connManager;

    public function setUp() {
        $configFilePath = TEST_ROOT . '/config/config.php';
        $configLoader = new ConfigLoader();
        $config = $configLoader->load($configFilePath);
        $appConfig = new AppConfig($config);

        $serviceContainer = new ServiceContainer(array());

        $appContext = new AppContext($appConfig, $serviceContainer);

        $serviceContainer->registerService('appContext', $appContext);

        $logger = new DumpLogger();
        $serviceContainer->registerService('logger', $logger);

        $databaseExtension = new DatabaseExtension();
        $databaseExtension->setAppContext($appContext);
        $databaseExtension->setServiceContainer($serviceContainer);
        $databaseExtension->load();

        $this->connManager = $appContext->getService('connManager');
    }

    public function testRead() {
        
    }

}
