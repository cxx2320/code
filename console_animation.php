<?php

$tmpStr = "\033[32;1m {step} \033[0m";

for ($i = 0; $i <= 100; $i++) {
    sleep(1);
    echo "\033[14D";
    echo str_replace('{step}', sprintf('%02d%% ', $i), $tmpStr);
}
echo PHP_EOL;