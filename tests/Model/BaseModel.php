<?php

namespace tests\Model;

use LumengPHP\Db\Model;
use LumengPHP\App;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class BaseModel extends Model {

    public function __construct() {
        $connManager = App::$context->getService('connManager');
        parent::__construct($connManager);
    }

}
