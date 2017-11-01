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
     * 属性注入注解，有@get、@post、@request、@session、@config、@service、@currentEvent
     */
    const T_PROPERTY_INJECTOR = 4;

    /**
     * 动作注解，有@keepDefault、@queued
     */
    const T_ACTION = 5;

    /**
     * 左圆括号"("
     */
    const T_LEFT_PARENTHESIS = 6;

    /**
     * 右圆括号")"
     */
    const T_RIGHT_PARENTHESIS = 7;

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
                $this->type == self::T_PROPERTY_INJECTOR ||
                $this->type == self::T_ACTION;
    }

}
