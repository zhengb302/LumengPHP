<?php

namespace LumengPHP\Http;

/**
 * DFA
 * 
 * accept strings: abc、ad
 * 
 * start state: 0
 * 
 * transition functions:
 * (0, a) -> 1
 * (1, b) -> 2
 * (1, d) -> 4
 * (2, c) -> 3
 * 
 * accept states: {3, 4}
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
class DFA {

    /**
     * @var array 转移函数
     * 结构：
     * [
     *     0 => {
     *         "a": 1
     *     },
     *     1 => {
     *         "b": 2,
     *         "d": 4,
     *     },
     *     2 => {
     *         "c": 3
     *     }
     * ]
     */
    private $transitionFunc = [];

    /**
     * @var int 状态数
     */
    private $stateCounter = 0;

    /**
     * @var array 接受状态集
     */
    private $acceptStates = [];

    public function __construct(array $inputs) {
        $this->build($inputs);
    }

    private function build($inputs) {
        foreach ($inputs as $input) {
            $this->buildFromInput($input);
        }
    }

    private function buildFromInput($input) {
        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            $ch = $input[$i];
            $this->transitionFunc[] = [
                $ch => ++$this->stateCounter,
            ];
        }

        $this->acceptStates[] = $this->stateCounter;
    }

    public function recognize($str) {
        $state = 0;
        for ($i = 0, $len = strlen($str); $i < $len; $i++) {
            $ch = $str[$i];

            //在当前状态上没转移
            if (!isset($this->transitionFunc[$state])) {
                return false;
            }

            //在当前状态上有转移，但是在当前字符上没转移
            $stateTransitionMap = $this->transitionFunc[$state];
            if (!isset($stateTransitionMap[$ch])) {
                return false;
            }

            //转移到下一个状态
            $state = $stateTransitionMap[$ch];
        }

        return in_array($state, $this->acceptStates);
    }

}
