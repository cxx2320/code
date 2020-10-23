<?php

/**
 * 简单的操作符实现(大体思路就是一个buildCondition入口，然后解析不同的操作符)
 */

define('PARAM_PREFIX', ':qp');

define('conditionBuilders', [
    'IN' => 'buildInCondition',
    'AND' => 'buildAndCondition'
]);

/**
 * 生成查询条件
 *
 * @param array $condition
 * @param array $params
 */
function buildCondition($condition, &$params)
{
    if (isset($condition[0])) { // 操作符
        $operator = strtoupper($condition[0]);
        $method = conditionBuilders[$operator] ?? 'buildSimpleCondition';
        array_shift($condition);
        return $method($operator, $condition, $params);
    } else { // 哈希
        return buildHashCondition($condition, $params);
    }
}

/**
 * 哈希
 */
function buildHashCondition($condition, &$params)
{
    $parts = [];
    foreach ($condition as $column => $value) {
        if (is_array($value)) {
            $parts[] = buildInCondition('IN', [$column, $value], $params);
        } else {
            if ($value === null) {
                $parts[] = "$column IS NULL";
            } else {
                $phName = PARAM_PREFIX . count($params);
                $parts[] = "$column=$phName";
                $params[$phName] = $value;
            }
        }
    }
    return count($parts) === 1 ? $parts[0] : '(' . implode(') AND (', $parts) . ')';
}

/**
 * in
 */
function buildInCondition($operator, $operands, &$params)
{
    if (!isset($operands[0], $operands[1])) {
        throw new Exception("Operator '$operator' requires two operands.");
    }

    list($column, $values) = $operands;

    if ($column === []) {
        // no columns to test against
        return $operator === 'IN' ? '0=1' : '';
    }
    if (!is_array($values) && !$values instanceof \Traversable) {
        // ensure values is an array
        $values = (array) $values;
    }

    $sqlValues = [];
    foreach ($values as $i => $value) {
        if (is_array($value) || $value instanceof \ArrayAccess) {
            $value = isset($value[$column]) ? $value[$column] : null;
        }
        if ($value === null) {
            $sqlValues[$i] = 'NULL';
        } else {
            $phName = PARAM_PREFIX . count($params);
            $params[$phName] = $value;
            $sqlValues[$i] = $phName;
        }
    }

    if (empty($sqlValues)) {
        return $operator === 'IN' ? '0=1' : '';
    }

    if (count($sqlValues) > 1) {
        return "$column $operator (" . implode(', ', $sqlValues) . ')';
    } else {
        $operator = $operator === 'IN' ? '=' : '<>';
        return $column . $operator . reset($sqlValues);
    }
}

/**
 * and
 */
function buildAndCondition($operator, $operands, &$params)
{
    $parts = [];
    foreach ($operands as $operand) {
        if (is_array($operand)) {
            $operand = buildCondition($operand, $params);
        }
        if ($operand !== '') {
            $parts[] = $operand;
        }
    }
    if (!empty($parts)) {
        return '(' . implode(") $operator (", $parts) . ')';
    } else {
        return '';
    }
}

function buildSimpleCondition($operator, $operands, &$params)
{
    if (count($operands) !== 2) {
        throw new Exception("Operator '$operator' requires two operands.");
    }

    [$column, $value] = $operands;

    if ($value === null) {
        return "$column $operator NULL";
    } else {
        $phName = PARAM_PREFIX . count($params);
        $params[$phName] = $value;
        return "$column $operator $phName";
    }
}

$params = [];
var_dump(buildCondition([
    'and',
    ['=', 'name', 'cxx'],
    ['=', 'age', '18'],
    [
        'and',
        'a=1',
        ['and', 'name=qqq'],
    ],
], $params));
