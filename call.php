<?php


class A
{
    private $x = 1;
}

// PHP 7 之前版本的代码
$getXCB = function () {
    return $this->x;
};

$getX = $getXCB->bindTo(new A(), 'A'); // 中间层闭包

// PHP 7+ 可以提高20%到50%性能
// $getX = function() {
//     return $this->x;
// };

// echo $getx->call(new A);
