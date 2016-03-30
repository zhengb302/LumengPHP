<?php

namespace tests\Commands;

/**
 * 可注入属性的Command
 *
 * @author Lumeng <zhengb302@163.com>
 */
class PropertyInjectionAwareCommand {

    /**
     * @var int 用户id
     * @query(user_id)
     */
    private $uid;

    /**
     * @var string 用户名
     * @request
     */
    private $name;

    /**
     * @var string 密码
     * @request
     */
    private $password;

    /**
     * @var int 年龄
     * @request
     */
    private $age;

    /**
     * @var UserModel 
     */
    private $userModel;

    /**
     * @var Logger 日志组件
     * @service
     */
    private $logger;

}
