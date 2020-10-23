# Yii2 的where实现
Yii框架中where设计非常巧妙，这里介绍了两种格式的使用。它具体实现是`yii\db\QueryBuilder`的`buildCondition`方法

```php
    /**
     * Parses the condition specification and generates the corresponding SQL expression.
     * @param string|array|Expression $condition the condition specification. Please refer to [[Query::where()]]
     * on how to specify a condition.
     * @param array $params the binding parameters to be populated
     * @return string the generated SQL expression
     */
    public function buildCondition($condition, &$params)
    {
        if ($condition instanceof Expression) {
            foreach ($condition->params as $n => $v) {
                $params[$n] = $v;
            }
            return $condition->expression;
        } elseif (!is_array($condition)) {
            return (string) $condition;
        } elseif (empty($condition)) {
            return '';
        }

        if (isset($condition[0])) { // operator format: operator, operand 1, operand 2, ...
            $operator = strtoupper($condition[0]);
            if (isset($this->conditionBuilders[$operator])) {
                $method = $this->conditionBuilders[$operator];
            } else {
                $method = 'buildSimpleCondition';
            }
            array_shift($condition);
            return $this->$method($operator, $condition, $params);
        } else { // hash format: 'column1' => 'value1', 'column2' => 'value2', ...
            return $this->buildHashCondition($condition, $params);
        }
    }

```
当传入`$condition` , `$params`后会返回
```sql
WHERE (`status` = :qp1) AND (`id` IN (:qp2, :qp3, :qp4))
```
`$params`将会被更改
```php
[
    ':qp1' => '1',
    ':qp2' => '5',
    ':qp3' => '45',
    ':qp4' => '82'
]
```
可以使用`PDOStatement::bindParam`进行参数绑定

## 哈希格式
```php
// ...WHERE (`status` = 10) AND (`type` IS NULL) AND (`id` IN (4, 8, 15))
$query->where([
    'status' => 10,
    'type' => null,
    'id' => [4, 8, 15],
]);
```
这种使用简单，只能实现一些简单的where语句,`buildHashCondition`方法就是实现哈希的实现。
```php
    /**
     * Creates a condition based on column-value pairs.
     * @param array $condition the condition specification.
     * @param array $params the binding parameters to be populated
     * @return string the generated SQL expression
     */
    public function buildHashCondition($condition, &$params)
    {
        $parts = [];
        foreach ($condition as $column => $value) {
            // 这里用来判断$value的类型，注释掉让它只判断数组的方式
            // if (ArrayHelper::isTraversable($value) || $value instanceof Query) { 
            if (is_array($value)) {
                // IN condition
                $parts[] = $this->buildInCondition('IN', [$column, $value], $params);
            } else {
                if (strpos($column, '(') === false) {
                    $column = $this->quoteColumnName($column);
                }
                if ($value === null) {
                    $parts[] = "$column IS NULL";
                } elseif ($value instanceof Expression) {
                    $parts[] = "$column=" . $value->expression;
                    foreach ($value->params as $n => $v) {
                        $params[$n] = $v;
                    }
                } else {
                    $phName = self::PARAM_PREFIX . count($params);
                    $parts[] = "$column=$phName";
                    $params[$phName] = $value;
                }
            }
        }
        // 类似 (a = cxx) AND (a = cxx) AND (a = cxx)
        return count($parts) === 1 ? $parts[0] : '(' . implode(') AND (', $parts) . ')';
    }
```
## 操作符格式
```php
[操作符, 操作数1, 操作数2, ...]
```
这种方式可以实现复杂的sql查询

```php
$params = [];
$bulider->buildCondition([
    'or',
    ['like', 'nickname', 'cxx'],
    ['like', 'name', 'cxx'],
    ['=', 'age', '18']
], $params);

// WHERE ("nickname" LIKE :qp0) OR ("name" LIKE :qp1) OR ("age" = :qp2)
// $params
// [
//     ":qp0" => "%cxx%"
//     ":qp1" => "%cxx%"
//     ":qp2" => "18"
// ]
```
操作符可选项为`$bulider->conditionBuilders`属性，每一个操作符都对应的有解析方法
```php
/**
 * @var array map of query condition to builder methods.
 * These methods are used by [[buildCondition]] to build SQL conditions from array syntax.
 */
protected $conditionBuilders = [
    'NOT' => 'buildNotCondition',
    'AND' => 'buildAndCondition',
    'OR' => 'buildAndCondition',
    'BETWEEN' => 'buildBetweenCondition',
    'NOT BETWEEN' => 'buildBetweenCondition',
    'IN' => 'buildInCondition',
    'NOT IN' => 'buildInCondition',
    'LIKE' => 'buildLikeCondition',
    'NOT LIKE' => 'buildLikeCondition',
    'OR LIKE' => 'buildLikeCondition',
    'OR NOT LIKE' => 'buildLikeCondition',
    'EXISTS' => 'buildExistsCondition',
    'NOT EXISTS' => 'buildExistsCondition',
];
```
如果是`<`,`>`,`=`等未在`$conditionBuilders`数组中，均会调用`buildSimpleCondition`方法来组成一个简单的查询

## 简单实现
通过复制简化`yii\db\QueryBuilder`里的一些方法来更清晰的理解和使用Yii的查询流程。[点击查看](./simpleWhere.php)