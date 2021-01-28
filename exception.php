<?php

/**
 * 异常会从上至下的匹配第一个符合的catch块.
 */
class FooException extends Exception
{
}

class BarException extends Exception
{
}

try {
    throw new \BarException('Exception');
} catch (\Exception $th) {
    var_dump(4);
} catch (\Throwable $th) {
    var_dump(1);
} catch (\FooException $th) {
    var_dump(2);
} catch (\BarException $th) {
    var_dump(3);
}
