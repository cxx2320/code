<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class A
{
    private $x = 1;
}

// PHP 7 之前版本的代码
$getXCB = function () {
    return $this->x;
};

$getX = $getXCB->bindTo(new A(), 'A'); // 中间层闭包

// // PHP 7+
// $getX = function() {
//     return $this->x;
// };

// echo $getx->call(new A);
