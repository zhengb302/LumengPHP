<?php

use LumengPHP\Kernel\AppContext;
use LumengPHP\Kernel\AppContextInterface;
use LumengPHP\Kernel\Event\EventManagerInterface;

/*
 * 基础通用函数
 */

/**
 * 获取环境变量的值
 * 
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
 * 
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
 * 
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
 * 
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
 * 去掉数组元素的某个字段
 * 
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
 * 
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
 * 按某个字段的值给rows分组
 * 
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
            $groups[$fieldVal] = [];
        }

        $groups[$fieldVal][] = $row;
    }

    return $groups;
}

/**
 * 捞出一组记录中某个字段的最大值
 * 
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
 * 
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
 * 
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

/**
 * 返回系统中<b>AppContextInterface</b>的实例
 * 
 * @return AppContextInterface
 */
function app_context() {
    return AppContext::getInstance();
}

/**
 * 取得应用配置数据
 * 
 * @param string $key 配置key
 * @return mixed|null 应用配置数据。如果配置不存在，则返回null
 */
function config($key) {
    return app_context()->getConfig($key);
}

/**
 * 获取一个服务对象
 * 
 * @param string $serviceName 服务名称
 * @return object|null 一个服务对象。如果服务不存在，则返回null
 */
function service($serviceName) {
    return app_context()->getService($serviceName);
}

/**
 * 返回事件管理器实例
 * 
 * @return EventManagerInterface
 */
function event_manager() {
    return service('eventManager');
}

/**
 * 触发一个事件
 * 
 * @param object $event 事件对象
 * @param bool $immediately 是否立即触发。如果此参数为<b>true</b>，对于队列化的异步事件，
 * 则不会把该事件放入队列中，而是立即执行其监听器；对于未队列化的同步事件，此参数不起任何作用。
 */
function trigger_event($event, $immediately = false) {
    event_manager()->trigger($event, $immediately);
}
