<?php

namespace tests\TestCases;

use LumengPHP\Db\Test\DatabaseTestCase;

/**
 * 数据库测试用例基类
 */
abstract class BaseDatabaseTestCase extends DatabaseTestCase {

    /**
     * @var \PDO PDO instance for test
     */
    private static $pdo;

    public function getPdo() {
        if (self::$pdo === null) {
            global $connectionConfigs;

            //create a PDO instance for test
            $dsn = $connectionConfigs['db']['dsn'];
            $username = $connectionConfigs['db']['username'];
            $password = $connectionConfigs['db']['password'];
            self::$pdo = new \PDO($dsn, $username, $password);

            //set charset for test database
            $dbCharset = $connectionConfigs['db']['charset'];
            self::$pdo->query("SET NAMES {$dbCharset}");
        }

        return self::$pdo;
    }

}
