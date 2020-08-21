<?php


function tree($data = [])
{
    foreach ($data as $key => $value) {
        $items[$value['id']] = $value;
    }
    $tree = [];
    foreach ($items as $key => $value) {
        if ($value['pid'] > 0) {
            $items[$value['pid']]['childer'][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}

$data = [
    ['id' => '1', 'name' => '分类1', 'pid' => '0'],
    ['id' => '2', 'name' => '分类2', 'pid' => '1'],
    ['id' => '3', 'name' => '分类3', 'pid' => '0'],
    ['id' => '4', 'name' => '分类4', 'pid' => '3'],
    ['id' => '5', 'name' => '分类5', 'pid' => '4'],
    ['id' => '6', 'name' => '分类6', 'pid' => '1'],
    ['id' => '7', 'name' => '分类7', 'pid' => '5'],
    ['id' => '8', 'name' => '分类8', 'pid' => '4']
];

var_export(tree($data));
