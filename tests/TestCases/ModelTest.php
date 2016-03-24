<?php

namespace tests\TestCases;

use tests\Model\UserModel;

/**
 * Description of ModelTest
 *
 * @author Lumeng <zhengb302@163.com>
 */
class ModelTest extends \PHPUnit_Framework_TestCase {

    public function testRead() {
        $userModel = new UserModel;
        $user = $userModel->where(array('id' => 1))->find();
        $this->assertNotFalse($user);
    }

}
