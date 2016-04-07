<?php

namespace tests\TestCases;

use tests\Commands\PropertyInjectionCommand;
use tests\Misc\MockContainer;
use LumengPHP\DependencyInjection\ContainerCollection;
use LumengPHP\DependencyInjection\PropertyInjection\PropertyInjector;
use tests\Misc\DumpLogger;

/**
 * 属性注入测试
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjectTest extends \PHPUnit_Framework_TestCase {

    public function testInject() {
        //容器集合
        $containerCollection = new ContainerCollection();

        //query容器
        $queryContainer = new MockContainer(array('user_id' => 8));
        $containerCollection->add('query', $queryContainer);

        //request容器
        $requestContainer = new MockContainer(array(
            'name' => 'zhangsan',
            'password' => '123456',
            'userAge' => 18,
        ));
        $containerCollection->add('request', $requestContainer);

        //service容器
        $serviceContainer = new MockContainer(array(
            'logger' => new DumpLogger(),
        ));
        $containerCollection->add('service', $serviceContainer);

        //可注入属性的对象
        $cmd = new PropertyInjectionCommand();

        //属性注入元数据
        $metadataList = require(TEST_ROOT . '/resources/property-injection-aware-command-dump.php');

        //属性注射器
        $injector = new PropertyInjector($containerCollection, $cmd, $metadataList);
        $injector->doInject();

        $this->assertAttributeEquals('8', 'uid', $cmd);
        $this->assertAttributeEquals('zhangsan', 'name', $cmd);
        $this->assertAttributeEquals(18, 'age', $cmd);

        $this->assertAttributeEmpty('userModel', $cmd);

        $this->assertAttributeNotEmpty('logger', $cmd);
        $this->assertAttributeInstanceOf('tests\Misc\DumpLogger', 'logger', $cmd);
    }

}
