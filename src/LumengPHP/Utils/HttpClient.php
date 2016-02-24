<?php

namespace LumengPHP\Utils;

/**
 * Http客户端
 *
 * @author zhengluming <luming.zheng@baozun.cn>
 */
class HttpClient {

    public static function get($url, array $params = array()) {
        $fullUrl = UrlHelper::appendParams($url, $params);
        return file_get_contents($fullUrl);
    }

    public static function getJson($url, array $params = array()) {
        $response = self::get($url, $params);
        return self::responseToJson($response);
    }

    public static function post($url, array $data, $returnJson = false) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        return $returnJson ? self::responseToJson($response) : $response;
    }

    private static function responseToJson($response) {
        if ($response === false) {
            return null;
        }

        $result = json_decode($response, true);
        return is_array($result) ? $result : null;
    }

}
