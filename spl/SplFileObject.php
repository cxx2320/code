<?php

/**
 * php读取大数据文件（ SplFileObject 继承 SplFileInfo ）
 */
$num = 0;
$file = new \SplFileObject(__DIR__ . '/ip.txt');
foreach ($file as $line_num => $line) {
    // echo "Line $line_num is $line \n";
    $num += strlen($line);
}
var_dump($num);
