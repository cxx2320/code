<?php


/**
 * 使用ArrayAccess可以使把对象以数组的方式访问.
 */
class TestArray implements \ArrayAccess
{
    public $arr = [];

    public function __construct(array $values = [])
    {
        $this->arr = $values;
    }

    /**
     * 检查一个偏移位置是否存在.
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->arr[$offset]);
    }

    /**
     * 获取一个偏移位置的值
     */
    public function offsetGet($key)
    {
        return $this->arr[$key];
    }

    /**
     * 设置一个偏移位置的值
     */
    public function offsetSet($key, $value)
    {
        $this->arr[$key] = $value;
    }

    /**
     * 复位一个偏移位置的值
     */
    public function offsetUnset($key)
    {
        unset($this->arr[$key]);
    }
}

$obj_array = new TestArray([
    'name' => 'cxx',
    'age'  => 18,
    'bio'  => 'a super hero',
]);

var_dump($obj_array['name']);
$obj_array['name'] = 'Cxx';
var_dump($obj_array['name']);

unset($obj_array['name']);
var_dump($obj_array['name'] ?? null);
