<?php

namespace LumengPHP\Kernel\Annotation\Parser;

use Exception;
use Djj\Annotation\MetaData;

/**
 * 语法分析器
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class Parser {

    /**
     * @var Lexer 词法分析器对象
     */
    private $lexer;

    /**
     * @var MetaData 用于保存语法分析过程中生成的元数据
     */
    private $metaData;

    /**
     * @var Token 超前一个token
     */
    private $lookahead;

    /**
     * @var Token 上一个token，也就是lookahead前的一个token
     */
    private $lastToken;

    public function __construct(Lexer $lexer, MetaData $metaData) {
        $this->lexer = $lexer;
        $this->metaData = $metaData;

        //忽略掉前面的注释，直达第一个注解
        $this->lexer->gotoNextAnnotation();

        //lookahead
        $this->consume();
    }

    public function parse() {
        while ($this->lookahead->getType() != Token::T_END) {
            $lookaheadTokenType = $this->lookahead->getType();
            if ($lookaheadTokenType == Token::T_VAR) {
                $this->varTag();
            } elseif ($lookaheadTokenType == Token::T_REQUEST_PARAM) {
                $this->requestParamTag();
            } elseif ($lookaheadTokenType == Token::T_CAMEL_CASE) {
                $this->camelCaseTag();
            } elseif ($lookaheadTokenType == Token::T_UNKNOWN_ANNOTATION) {
                $this->unknownTag();
            } else {
                throw new Exception('语法错误');
            }
        }
    }

    /**
     * "@var"注解
     */
    private function varTag() {
        $this->match(Token::T_VAR);
        $this->match(Token::T_ID, true);
        $this->metaData->addMetaData('type', $this->lastToken->getText());
    }

    /**
     * "@get"、"@post"、"@request"、"@session"注解
     */
    private function requestParamTag() {
        $this->match(Token::T_REQUEST_PARAM);
        $this->metaData->addMetaData('source', ltrim($this->lastToken->getText(), '@'));
        if ($this->lookahead->getType() == Token::T_LEFT_PARENTHESIS) {
            $this->match(Token::T_LEFT_PARENTHESIS);
            $this->match(Token::T_ID);
            $this->metaData->addMetaData('paramName', $this->lastToken->getText());
            $this->match(Token::T_RIGHT_PARENTHESIS);
        }

        if (!$this->lookahead->isAnnotation()) {
            $this->lexer->gotoNextAnnotation();
            $this->consume();
        }
    }

    /**
     * "@camelCase"注解
     */
    private function camelCaseTag() {
        $this->match(Token::T_CAMEL_CASE, true);
        $this->metaData->addMetaData('camelCase', true);
    }

    /**
     * 未知注解
     */
    private function unknownTag() {
        $this->match(Token::T_UNKNOWN_ANNOTATION, true);
    }

    /**
     * 
     * @param int $x
     * @param bool $gotoNextAnnotation 是否直接去往下一个注解
     */
    private function match($x, $gotoNextAnnotation = false) {
        if ($this->lookahead->getType() == $x) {
            if ($gotoNextAnnotation) {
                $this->lexer->gotoNextAnnotation();
            }
            $this->consume();
        } else {
            throw new Exception('语法错误');
        }
    }

    private function consume() {
        $this->lastToken = $this->lookahead;

        //lookahead
        $this->lookahead = $this->lexer->nextToken();
    }

}
