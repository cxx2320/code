<?php
trait Name
{
    public $k = false;
}

class Cxx
{
    use Name;
    public $age = 18;
    public $name = 'cxx';
    public static $qq = '232xxxxx';
    protected $email = '23xxxxxx@qq.com';
    private $mobile = '132xxxxx';
    const SEX = 1;

    public function __construct()
    {
        echo 1;
    }
}

$ser = serialize(new Cxx());
/**
 * O:3:"Cxx":5:{s:3:"age";i:18;s:4:"name";s:3:"cxx";s:8:"*email";s:15:"23xxxxxx@qq.com";s:11:"Cxxmobile";s:8:"132xxxxx";s:1:"k";b:0;}
 * 只会序列对象的公共，受保护，私有的非静态属性
 */
var_dump($ser);

/**
 * 解序列化不会调用类的构造函数
 */
// $ser = 'O:3:"Cxx":5:{s:3:"age";i:18;s:4:"name";s:3:"cxx";s:8:"*email";s:15:"23xxxxxx@qq.com";s:11:"Cxxmobile";s:8:"132xxxxx";s:1:"k";b:0;}';
$obj = unserialize($ser);
var_dump($obj);exit;