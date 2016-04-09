<?php

/*
 * 检查应用的安装情况
 */

$appDir = dirname(dirname(dirname(dir(__DIR__))));
echo "app dir: ", $appDir, "\n";

file_put_contents($appDir, $appDir . '/what-the-fuck.log');
