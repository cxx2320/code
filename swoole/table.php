<?php


use Swoole\Table;

$table_config = [
    'u2fd' => [
        'size'    => 10240,
        'columns' => [
            ['name' => 'fd', 'type' => Table::TYPE_INT, 'size' => 8],
        ],
    ],
    'fd2u' => [
        'size'    => 10240,
        'columns' => [
            ['name' => 'uid', 'type' => Table::TYPE_INT, 'size' => 8],
        ],
    ],
    'vote' => [
        'size'    => 256,
        'columns' => [
            ['name' => 'poll', 'type' => Table::TYPE_INT, 'size' => 8], //票数
        ],
    ],
];

$tables = [];

foreach ($table_config as $table_name => $table) {
    $table_obj = new Table($table['size']);
    foreach ($table['columns'] as $column) {
        $table_obj->column($column['name'], $column['type'], $column['size']);
    }
    $table_obj->create();
    $tables[$table_name] = $table_obj;
}
var_dump($tables);
