<?php

namespace LumengPHP\Kernel\Annotation\Parser;

use Exception;

/**
 * 词法分析器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Lexer {

    /**
     * 表示输入结束
     */
    const EOF = -1;

    /**
     * @var string 输入字符串
     */
    private $docComment;

    /**
     * @var int 输入字符串长度
     */
    private $len;

    /**
     * @var int 当前位置
     */
    private $i;

    /**
     * @var string lookahead字符
     */
    private $ch;

    public function __construct($docComment) {
        $this->docComment = $docComment;
        $this->len = strlen($this->docComment);
        $this->i = 0;

        //lookahead
        $this->ch = $docComment[0];
    }

    public function nextToken() {
        while ($this->ch != self::EOF) {
            switch ($this->ch) {
                case ' ':
                case "\t":
                case "\n":
                case "\r":
                    $this->WS();
                    continue;
                case '@':
                    return $this->ANNOTATION();
                case '(':
                    $this->consume();
                    return new Token(Token::T_LEFT_PARENTHESIS, '(');
                case ')':
                    $this->consume();
                    return new Token(Token::T_RIGHT_PARENTHESIS, ')');
                default:
                    if ($this->isLETTER()) {
                        return $this->ID();
                    } else {
                        $this->consume();
                    }
            }
        }

        return new Token(Token::T_END, 'END');
    }

    /**
     * (直接)去往下一个注解
     */
    public function gotoNextAnnotation() {
        while ($this->ch != self::EOF) {
            //lookahead字符是@，标识下一个注解的开始
            if ($this->ch == '@') {
                return;
            } else {
                $this->consume();
            }
        }
    }

    /**
     * 忽略空白字符，空白字符：(' '|'\t'|'\n'|'\r')*
     */
    private function WS() {
        while (ctype_space($this->ch)) {
            $this->consume();
        }
    }

    /**
     * 识别注解
     * @return Token
     * @throws Exception
     */
    private function ANNOTATION() {
        $text = '';
        do {
            $text .= $this->ch;
            $this->consume();
        } while ($this->isLETTER());

        $tokenType = null;
        switch ($text) {
            case '@var':
                $tokenType = Token::T_VAR;
                break;
            case '@get':
            case '@post':
            case '@request':
            case '@session':
            case '@config':
            case '@service':
            case '@currentEvent':
                $tokenType = Token::T_PROPERTY_INJECTOR;
                break;
            case '@keepDefault':
            case '@queued':
                $tokenType = Token::T_ACTION;
                break;
            default:
                $tokenType = Token::T_UNKNOWN_ANNOTATION;
        }
        return new Token($tokenType, $text);
    }

    /**
     * 识别标识符
     * @return Token
     */
    private function ID() {
        $text = '';
        do {
            $text .= $this->ch;
            $this->consume();
        } while ($this->isLETTER() || $this->isNUMBER() || $this->ch == '_');

        return new Token(Token::T_ID, $text);
    }

    /**
     * 向前移动一个字符，同时检测输入的结束
     */
    private function consume() {
        $this->i++;
        if ($this->i >= $this->len) {
            $this->ch = self::EOF;
        } else {
            $this->ch = $this->docComment[$this->i];
        }
    }

    /**
     * 是否是英文字母
     * @return bool
     */
    private function isLETTER() {
        return $this->ch >= 'a' && $this->ch <= 'z' ||
                $this->ch >= 'A' && $this->ch <= 'Z';
    }

    /**
     * 是否是数字
     * @return bool
     */
    private function isNUMBER() {
        return $this->ch >= '0' && $this->ch <= '9';
    }

}
