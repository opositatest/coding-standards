<?php

declare(strict_types=1);

$config = new Opositatest\PhpCsFixerConfig\StrictConfig();
$config->getFinder()->in('src');

$config->setCacheFile(__DIR__ . '/.php_cs.cache');

return $config;
