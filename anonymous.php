<?php



$obj = new class() {
    public $name = 'cxx';
    public $age  = 18;
};

/**
 * Q:如果匿名类被序列化和反序列化会怎么样？
 * A:显然是不允许的。
 */
var_dump(serialize($obj)); // PHP Fatal error:  Uncaught Exception: Serialization of 'class@anonymous' is not allowed
