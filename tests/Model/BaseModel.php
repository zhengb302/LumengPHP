<?php

namespace tests\Model;

use LumengPHP\Db\Model;
use LumengPHP\Facades\App;

/**
 * Model基类
 *
 * @author Lumeng <zhengb302@163.com>
 */
abstract class BaseModel extends Model {

    public function __construct() {
        $connManager = App::getService('connManager');
        parent::__construct($connManager);
    }

}
