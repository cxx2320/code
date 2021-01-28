<?php

/**
 * 类的静态属性将会在全局被共享，如果被继承的子类修改，那么父类也会被改变（是一个单例的属性），如果子类覆盖了父类的静态属性，
 * 那么这两个相同的静态属性将会毫无关联.
 *
 * 如果使用trait定义静态属性，静态属性不会被多个类之间共享 https://www.php.net/trait#107965
 */
class Model
{
    public static $db = null;
}

class User extends Model
{
    // public static $db = null;
}

Model::$db = ('mysql');
User::$db  = ('pgsql');

var_dump(User::$db);
var_dump(Model::$db);
