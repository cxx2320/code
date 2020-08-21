<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$list = [1, 3, 2, 6, 0, 6, 5];

// $i = 1 max $j = 6
$list1 = [3, 1, 2, 6, 0, 6, 5];
$list2 = [3, 2, 1, 6, 0, 6, 5];
$list3 = [3, 2, 6, 1, 0, 6, 5];
$list4 = [3, 2, 6, 1, 6, 0, 5];
$list5 = [3, 2, 6, 1, 6, 5, 0];
$list6 = [3, 2, 6, 1, 6, 5, 0];

// $i = 2; max $j = 5
$list1 = [3, 2, 6, 1, 6, 5, 0];
$list2 = [3, 6, 2, 1, 6, 5, 0];
$list3 = [3, 6, 2, 1, 6, 5, 0];
$list4 = [3, 6, 2, 6, 1, 5, 0];
$list5 = [3, 6, 2, 6, 5, 1, 0];

// $i = 3; max $j = 4
$list1 = [6, 3, 2, 6, 5, 1, 0];
$list2 = [6, 3, 2, 6, 5, 1, 0];
$list3 = [6, 3, 6, 2, 5, 1, 0];
$list4 = [6, 3, 6, 5, 2, 1, 0];

// $i = 4; max $j = 3
$list1 = [6, 3, 6, 5, 2, 1, 0];
$list2 = [6, 6, 3, 5, 2, 1, 0];
$list3 = [6, 6, 5, 3, 2, 1, 0];

// $i = 5; max $j = 2
$list1 = [6, 6, 5, 3, 2, 1, 0];
$list2 = [6, 6, 5, 3, 2, 1, 0];

// $i = 6; max $j = 1
$list1 = [6, 6, 5, 3, 2, 1, 0];

//最大循环次数 7
for ($i = 1; $i < count($list); ++$i) {
    // 最大循环次数 6
    for ($j = 0; $j < count($list) - $i; ++$j) {
        // $j = 6;
        if ($list[$j] < $list[$j + 1]) {
            [$list[$j], $list[$j + 1]]  = [$list[$j + 1], $list[$j]];
        }
    }
}

var_dump($list);

function dd(...$val)
{
    var_dump(...$val);
    exit;
}
