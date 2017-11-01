<?php

namespace LumengPHP\Kernel\Annotation\Parser;

use Exception;
use LumengPHP\Kernel\Annotation\Metadata;

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
     * @var Metadata 用于保存语法分析过程中生成的元数据
     */
    private $metadata;

    /**
     * @var Token 超前一个token
     */
    private $lookahead;

    /**
     * @var Token 上一个token，也就是lookahead前的一个token
     */
    private $lastToken;

    public function __construct(Lexer $lexer, Metadata $metadata) {
        $this->lexer = $lexer;
        $this->metadata = $metadata;

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
            } elseif ($lookaheadTokenType == Token::T_PROPERTY_INJECTOR) {
                $this->propertyInjectorTag();
            } elseif ($lookaheadTokenType == Token::T_ACTION) {
                $this->actionTag();
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
        $this->metadata->addMetadata('type', $this->lastToken->getText());
    }

    /**
     * 属性注入注解："@get"、"@post"、"@request"、"@session"、"@config"、"@service"、"@currentEvent"
     */
    private function propertyInjectorTag() {
        $this->match(Token::T_PROPERTY_INJECTOR);
        $this->metadata->addMetadata('source', ltrim($this->lastToken->getText(), '@'));
        if ($this->lookahead->getType() == Token::T_LEFT_PARENTHESIS) {
            $this->match(Token::T_LEFT_PARENTHESIS);
            $this->match(Token::T_ID);
            $this->metadata->addMetadata('paramName', $this->lastToken->getText());
            $this->match(Token::T_RIGHT_PARENTHESIS);
        }

        if (!$this->lookahead->isAnnotation()) {
            $this->lexer->gotoNextAnnotation();
            $this->consume();
        }
    }

    /**
     * 动作注解：“@keepDefault”、“@queued”
     */
    private function actionTag() {
        $this->match(Token::T_ACTION, true);

        $action = ltrim($this->lastToken->getText(), '@');
        $value = true;

        if ($this->lookahead->getType() == Token::T_LEFT_PARENTHESIS) {
            $this->match(Token::T_LEFT_PARENTHESIS);
            $this->match(Token::T_ID);
            $value = $this->lastToken->getText();
            $this->match(Token::T_RIGHT_PARENTHESIS);
        }

        $this->metadata->addMetadata($action, $value);

        if (!$this->lookahead->isAnnotation()) {
            $this->lexer->gotoNextAnnotation();
            $this->consume();
        }
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
