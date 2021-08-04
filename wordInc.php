<?php

/**
 * 单词自增序列 (生成的条目数为 $level * 26 )
 *
 * @param integer $level
 * @return array
 */
function wordInc(int $level = 1): array
{
    $words = range('A', 'Z');
    if ($level <= 1) {
        return $words;
    }
    $level--;
    for ($j = 0; $j < $level; $j++) {
        for ($i = 0; $i < 26; $i++) {
            $words[] = $words[$j] . $words[$i];
        }
    }
    return $words;
}

$w = wordInc(1);

print_r($w);

// Array
// (
//     [0] => A
//     [1] => B
//     [2] => C
//     [3] => D
//     [4] => E
//     [5] => F
//     [6] => G
//     [7] => H
//     [8] => I
//     [9] => J
//     [10] => K
//     [11] => L
//     [12] => M
//     [13] => N
//     [14] => O
//     [15] => P
//     [16] => Q
//     [17] => R
//     [18] => S
//     [19] => T
//     [20] => U
//     [21] => V
//     [22] => W
//     [23] => X
//     [24] => Y
//     [25] => Z
// )
