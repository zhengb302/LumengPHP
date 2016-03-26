<?php

namespace tests\TestCases;

use LumengPHP\Kernel\AppConfig;
use LumengPHP\DependencyInjection\ServiceContainer;
use LumengPHP\Kernel\AppContextImpl;
use LumengPHP\Extensions\DatabaseExtension;
use LumengPHP\Db\ConnectionManager;
use tests\Misc\DumpLogger;
use tests\Model\UserModel;

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
        $configs = require(TEST_ROOT . '/config/config.php');
        $appConfig = new AppConfig($configs);
        $serviceContainer = new ServiceContainer(array());
        $appContext = new AppContextImpl($appConfig, $serviceContainer);

        $logger = new DumpLogger();
        $serviceContainer->registerService('logger', $logger);
        $databaseExtension = new DatabaseExtension();
        $databaseExtension->load($appContext, $serviceContainer);

        $this->connManager = $appContext->getService('connManager');
    }

    public function testRead() {
        $userModel = new UserModel($this->connManager);
        $user = $userModel->where(array('id' => 1))->find();
        $this->assertNotFalse($user);
    }

}
