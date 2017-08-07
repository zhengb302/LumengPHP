<?php

namespace LumengPHP\Kernel\Annotation\Parser;

/**
 * Token
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Token {

    /**
     * 结束
     */
    const T_END = 0;

    /**
     * 标识符，由英文字母开头，英文字符、数字及下划线组成
     */
    const T_ID = 1;

    /**
     * 未知的注解
     */
    const T_UNKNOWN_ANNOTATION = 2;

    /**
     * var注解，即：@var
     */
    const T_VAR = 3;

    /**
     * 请求参数注入注解，有@get、@post、@request、@session、@container
     */
    const T_REQUEST_PARAM = 4;

    /**
     * 左圆括号"("
     */
    const T_LEFT_PARENTHESIS = 5;

    /**
     * 右圆括号")"
     */
    const T_RIGHT_PARENTHESIS = 6;

    /**
     * @var int token编号
     */
    private $type;

    /**
     * @var string token对应的文本字符串
     */
    private $text;

    public function __construct($type, $text) {
        $this->type = $type;
        $this->text = $text;
    }

    public function getType() {
        return $this->type;
    }

    public function getText() {
        return $this->text;
    }

    public function isAnnotation() {
        return $this->type == self::T_UNKNOWN_ANNOTATION ||
                $this->type == self::T_VAR ||
                $this->type == self::T_REQUEST_PARAM;
    }

}
