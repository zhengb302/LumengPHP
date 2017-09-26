<?php

namespace LumengPHP\Http;

use Exception;

/**
 * 拦截器匹配器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class InterceptorMatcher {

    /**
     * @var string 请求的PATH_INFO，例如：/user/login
     */
    private $pathInfo;

    /**
     * @var array 拦截器列表，格式：拦截器类全限定名称 => 拦截模式
     * 示例：
     * [
     *     Profiler::class => '*',
     *     LogFlusher::class => '*',
     *     UserAuth::class => '*, ~/user/login, ~/other/help',
     * ]
     */
    private $interceptors;

    public function __construct($pathInfo, array $interceptors) {
        $this->pathInfo = $pathInfo;
        $this->interceptors = $interceptors;
    }

    /**
     * 逐个匹配拦截器的拦截模式，并返回匹配到的拦截器列表
     * 
     * @return array
     */
    public function match() {
        $matchedInterceptors = [];
        foreach ($this->interceptors as $interceptor => $rawPatterns) {
            if ($this->isMatch($rawPatterns)) {
                $matchedInterceptors[] = $interceptor;
            }
        }

        return $matchedInterceptors;
    }

    /**
     * 当前请求的PATH_INFO是否匹配当前拦截器
     * 只有在匹配到了“正常模式”且不匹配任何“排除模式”的情况下，才匹配当前拦截器
     * 
     * @param string $rawPatterns
     * @return bool
     * @throws Exception
     */
    private function isMatch($rawPatterns) {
        $matched = false;
        foreach (explode(',', $rawPatterns) as $rawPattern) {
            $pattern = trim($rawPattern);
            if (!$pattern) {
                throw new Exception("拦截器匹配发生错误，错误的拦截模式：{$rawPatterns}");
            }

            //以波浪号开头的是“排除模式”，一旦匹配到一个“排除模式”，
            //则马上宣告当前拦截器不匹配此pathinfo，“排除模式”的优先级大于正常模式
            if ($pattern[0] == '~') {
                $pattern = substr($pattern, 1);
                if ($this->matchPattern($pattern)) {
                    return false;
                }
            }
            //正常模式
            else {
                //如果匹配到了某个“正常模式”，则跳过后边的“正常模式”
                if ($matched) {
                    continue;
                }

                $matched = $this->matchPattern($pattern);
            }
        }

        return $matched;
    }

    /**
     * 是否当前请求的PATH_INFO匹配模式
     * 
     * @param string $pattern
     * @return bool true表示匹配，false表示不匹配
     * @throws Exception
     */
    private function matchPattern($pattern) {
        $regex = $this->patternToRegex($pattern);
        $result = preg_match($regex, $this->pathInfo);
        if ($result === false) {
            throw new Exception("拦截器匹配发生错误，错误的pathinfo模式：{$pattern}");
        }

        return $result == 1;
    }

    /**
     * 把模式转化为正则表达式
     * 
     * @param string $pattern
     * @return string
     */
    private function patternToRegex($pattern) {
        $regex = '#^' . str_replace('*', '[A-Za-z0-9/_\-]*', $pattern) . '$#';
        return $regex;
    }

}
