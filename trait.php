<?php

/**
 * 以复制粘贴的方式理解trait
 * @link https://www.php.net/trait#120038
 */
trait Say
{
    public function getTrait()
    {
        return __TRAIT__;
    }

    /**
     * @link https://www.php.net/trait#113554
     *
     * @return void
     */
    public function getClass()
    {
        return __CLASS__; // 这个__CLASS__是被use的类名，如果子类继承父类，但是父类有使用trait而子类并没有使用，__CLASS__则是父类的类名
    }
}

class Foo
{
    use Say;
}
var_dump((new Foo)->getTrait());
var_dump((new Foo)->getClass());
