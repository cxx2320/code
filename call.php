<?php
/*
 * @Author: cxx<cxx2320@foxmail.com>
 * @Date: 2020-08-06 16:02:16
 * @LastEditors: cxx
 * @LastEditTime: 2020-08-06 16:04:54
 */
class A
{
    private $x = 1;
}

// PHP 7 之前版本的代码
$getXCB = function () {
    return $this->x;
};

$getX = $getXCB->bindTo(new A, 'A'); // 中间层闭包

// // PHP 7+
// $getX = function() {
//     return $this->x;
// };

// echo $getx->call(new A);
