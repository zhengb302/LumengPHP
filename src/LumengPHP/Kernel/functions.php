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

/**
 * 是否一个给定的字符串以一个子串结尾
 * @param string $haystack 
 * @param string $needle 
 * @param bool $caseInsensitive 是否大小写不敏感，默认大小写敏感
 * @return bool
 */
function ends_with($haystack, $needle, $caseInsensitive = false) {
    $pieceLen = strlen($needle);
    $tailSubStr = substr($haystack, -$pieceLen);

    if ($caseInsensitive) {
        $tailSubStr = strtolower($tailSubStr);
        $needle = strtolower($needle);
    }

    return $tailSubStr == $needle;
}

/**
 * 返回数组的最后一个元素
 * @param array $array
 * @return mixed|null 如果是空数组，则返回null，否则返回数组最后一个元素
 */
function array_last(array $array) {
    $len = count($array);
    if ($len == 0) {
        return null;
    }

    return $array[$len - 1];
}
