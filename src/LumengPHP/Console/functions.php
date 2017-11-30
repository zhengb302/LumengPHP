<?php

/*
 * 控制台应用相关的通用函数
 */

use LumengPHP\Console\InputInterface;

/**
 * 返回控制台应用中的<b>InputInterface</b>实例
 * 
 * 注意：只在控制台应用中有效
 * 
 * @return InputInterface
 */
function input() {
    return service('input');
}
