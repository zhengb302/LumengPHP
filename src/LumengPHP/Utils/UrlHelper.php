<?php

namespace LumengPHP\Utils;

/**
 * Url相关帮助程序
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class UrlHelper {

    /**
     * 在 url 后边附加参数
     * @param string $url
     * @param array $params 要附加的参数，key => value 形式
     * @return string
     */
    public static function appendParams($url, array $params) {
        $queryString = http_build_query($params);
        if (strpos($url, '?') === false) {
            return $url . '?' . $queryString;
        }

        $lastChar = $url[strlen($url) - 1];
        if (!in_array($lastChar, array('?', '&'))) {
            $url = $url . '&' . $queryString;
        } else {
            $url = $url . $queryString;
        }
        return $url;
    }

}
