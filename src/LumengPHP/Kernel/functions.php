<?php

/*
 * 通用函数
 */

/**
 * 获取环境变量的值
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        $value = $default;
    }

    return $value;
}
