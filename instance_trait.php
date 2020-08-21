<?php

/*
 * This file is part of the mingzaily/lumen-permission.
 *
 * (c) mingzaily <mingzaily@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

trait Singleton
{
    protected static $_instance;

    final public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    private function __construct()
    {
        $this->init();
    }

    protected function init()
    {
    }
}

class Db
{
    use Singleton;

    protected function init()
    {
    }
}
