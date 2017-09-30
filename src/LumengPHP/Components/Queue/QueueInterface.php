<?php

namespace LumengPHP\Components\Queue;

/**
 * 队列接口，代表一个队列，抽象并简化队列的使用
 *
 * @author zhengluming <luming.zheng@shandjj.com>
 */
interface QueueInterface {

    /**
     * 入队
     * 
     * @param mixed $element 要入队的元素，入队的时候会序列化此元素
     */
    public function enqueue($element);

    /**
     * 出队
     * 
     * @return mixed|null 删除并返回队列首部的元素（返回之前会反序列化此元素）。
     * 
     * 对于阻塞型的队列，如果队列里没有元素，那么此方法会一直阻塞，直到有新的元素入队，
     * 或者如果超时，返回<b>null</b>。
     * 
     * 对于非阻塞型的队列，如果队列为空，则返回<b>null</b>。
     */
    public function dequeue();
}
