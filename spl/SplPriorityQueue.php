<?php

/**
 * 优先级队列
 */

$queue = new SplPriorityQueue();

/**
 * $priority参数大的优先出列
 */
$queue->insert('2', 22);
$queue->insert('3', 71);
$queue->insert('4', 70);
$queue->insert('5', 20);
$queue->insert('6', 100);
// var_dump($queue->top());

/**
 * 出列（并且从队列中移除）
 */
// $queue->extract();

// var_dump($queue->count());


/**
 * 通过继承SplPriorityQueue并且覆盖compare达到重写优先级处理机制
 */
function compare($priority1, $priority2): int
{
    // return $priority1 - $priority2;//高优先级优先
    return $priority2 - $priority1; //低优先级优先
}
