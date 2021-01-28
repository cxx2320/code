<?php

$doubly = new SplDoublyLinkedList();
$doubly->push(1);
$doubly->push(2);
$doubly->push(3);
$doubly->push(4);

// 初始结构
// var_dump($doubly);

/*
 * 设置输出模式
 *  SplDoublyLinkedList::IT_MODE_FIFO 先进先出
 *  SplDoublyLinkedList::IT_MODE_LIFO 后进先出
 *  SplDoublyLinkedList::IT_MODE_KEEP 不删除模式
 *  SplDoublyLinkedList::IT_MODE_DELETE 删除模式
 */
$doubly->setIteratorMode(
    SplDoublyLinkedList::IT_MODE_LIFO
        |
        SplDoublyLinkedList::IT_MODE_DELETE
);

/*
 * 将迭代器倒回初始位置
 */
$doubly->rewind();
foreach ($doubly as $key => $value) {
    // if ($key == 1) {
    //     break;
    // }
    echo $key . ' ' . $value . "\n";
}

var_dump($doubly);
