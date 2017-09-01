<?php

/*
 * 基础通用函数
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
 * 抛出一个异常
 * @param string $message 异常消息
 * @param int $code 异常码
 * @param Throwable $previous 上一个异常
 * @throws Exception
 */
function _throw($message = '', $code = 0, Throwable $previous = null) {
    throw new Exception($message, $code, $previous);
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

/**
 * 过滤出数组中的某字段
 * @param array $rows 数组，其数组元素为关联数组
 * @param string $fieldName 字段名
 * @return array
 */
function filter_field(array $rows, $fieldName) {
    if (empty($rows)) {
        return [];
    }

    $fieldValArr = [];
    foreach ($rows as $row) {
        $fieldValArr[] = $row[$fieldName];
    }
    return $fieldValArr;
}

/**
 * 去掉数组元素的某个字段
 * @param array $rows 数组，其数组元素为关联数组
 * @param string $fieldName 要去掉的字段的字段名
 */
function unset_field(array &$rows, $fieldName) {
    for ($i = 0, $len = count($rows); $i < $len; $i++) {
        unset($rows[$i][$fieldName]);
    }
}

/**
 * 从rows捞出字段名称为$key、字段值为$val的行
 * @param array $rows
 * @param string $key
 * @param mixed $val
 * @return array
 */
function fishout_rows_by_key(array $rows, $key, $val) {
    if (empty($rows)) {
        return [];
    }

    $result = [];
    foreach ($rows as $row) {
        if ($row[$key] == $val) {
            $result[] = $row;
        }
    }
    return $result;
}

/**
 * 下标数组转换为Map（即关联数组）<br />
 * 例如执行以下代码：<br />
 * <pre>
 * // 原始下标数组：
 * $rows = array(
 *     array('id' => 28392, 'username' => 'zhangsan', 'nickname' => '张三'),
 *     array('id' => 28395, 'username' => 'lisi', 'nickname' => '李四'),
 * );
 * $userMap = index2map($rows, 'username', 'nickname');
 * </pre>
 * 则 $userMap 为：<br />
 * <pre>
 * array(
 *     'zhangsan' => '张三',
 *     'lisi' => '李四',
 * )
 * </pre>
 * @param array $rows 原始下标数组
 * @param string $keyName 取下标数组元素的此字段值作为Map的key，要求此字段的值唯一，
 * 否则后边的值会覆盖前面的值，导致结果不可预测
 * @param string $valueFieldName 取下标数组元素的此字段值作为Map的value。如果为false，则使用整个元素作为value
 * @return array Map（即关联数组）
 */
function index2map(array $rows, $keyName, $valueFieldName = false) {
    if (empty($rows)) {
        return [];
    }

    $map = [];
    foreach ($rows as $ele) {
        $key = $ele[$keyName];
        $map[$key] = $valueFieldName != false ? $ele[$valueFieldName] : $ele;
    }
    return $map;
}

/**
 * 按某个字段的值给rows分组
 * @param array $rows
 * @param string $fieldName 字段名
 * @return array 分组
 */
function group_by_field(array $rows, $fieldName) {
    if (empty($rows)) {
        return [];
    }

    $groups = [];
    foreach ($rows as $row) {
        $fieldVal = $row[$fieldName];
        if (!isset($groups[$fieldVal])) {
            $groups[$fieldVal] = array();
        }

        $groups[$fieldVal][] = $row;
    }

    return $groups;
}

/**
 * 捞出一组记录中某个字段的最大值
 * @param array $rows
 * @param string $fieldName 字段名称
 * @return mixed
 */
function fishout_max(array $rows, $fieldName) {
    $max = $rows[0][$fieldName];
    foreach ($rows as $row) {
        $value = $row[$fieldName];
        if ($value > $max) {
            $max = $value;
        }
    }

    return $max;
}

/**
 * 捞出一组记录中某个字段的最小值
 * @param array $rows
 * @param string $fieldName 字段名称
 * @return mixed
 */
function fishout_min(array $rows, $fieldName) {
    $min = $rows[0][$fieldName];
    foreach ($rows as $row) {
        $value = $row[$fieldName];
        if ($value < $min) {
            $min = $value;
        }
    }

    return $min;
}

/**
 * 根据一个已有给定顺序的值列表来排序一组记录
 * @param array $rows 待排序的一组记录
 * @param array $refValues 已有给定顺序的值列表
 * @param string $refFieldName 待排序记录里的参考字段名称
 * @param int $limitNum 限制的结果记录的数量，为0则表示不限制
 * @return array 已排序的记录
 */
function sort_by_values(array $rows, array $refValues, $refFieldName, $limitNum = 0) {
    $sortedRows = [];
    foreach ($refValues as $refValue) {
        if ($limitNum != 0 && count($sortedRows) == $limitNum) {
            break;
        }

        foreach ($rows as $i => $row) {
            if ($row[$refFieldName] == $refValue) {
                $sortedRows[] = $row;
                unset($rows[$i]);
                break;
            }
        }
    }

    return $sortedRows;
}
