<?php

/**
 * 获取指定位置的字母
 * @var int $indexValue
 */
function stringIncWord($indexValue)
{
    $base26 = null;
    do {
        $characterValue = ($indexValue % 26) ?: 26;
        $indexValue = ($indexValue - $characterValue) / 26;
        $base26 = chr($characterValue + 64) . ($base26 ?: '');
    } while ($indexValue > 0);
    return  $base26;
}