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
     * @from(query[user_id])
     */
    private $uid;

    /**
     * @var string 用户名
     * @from(request)
     */
    private $name;

    /**
     * @var string 密码
     * @from(request)
     */
    private $password;

    /**
     * @var int 年龄
     * @from(request[userAge])
     */
    private $age;

    /**
     * @var UserModel 
     */
    private $userModel;

    /**
     * @var Logger 日志组件
     * @from(service)
     */
    private $logger;

}
