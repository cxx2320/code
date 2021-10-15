<?php

/**
 * 单词序列转数字
 */
function wordToInt(string $word): int
{
    $map = [
        'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13,
        'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26,
        'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13,
        'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26,
    ];
    $int = 0;
    for ($i = strlen($word) - 1; $i >= 0; $i--) {
        if (!isset($map[$word[$i]])) {
            throw new \Exception("Error : Illegal String");
        }
        $int += $map[$word[$i]] * (26 ** $i);
    }
    return $int;
}

// var_dump(wordToInt('AAA'));
// 18278

// var_dump(wordToInt('AA'));
// 27


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


/**
 * 获取指定位置的单词序列
 * @var int $indexValue
 */
function stringIncWord(int $indexValue): string
{
    $base26 = null;
    do {
        $characterValue = ($indexValue % 26) ?: 26;
        $indexValue = ($indexValue - $characterValue) / 26;
        $base26 = chr($characterValue + 64) . ($base26 ?: '');
    } while ($indexValue > 0);
    return  $base26;
}

// var_dump(stringIncWord('27'));
// AA