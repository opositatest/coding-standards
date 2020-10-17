<?php

declare(strict_types=1);

use Opositatest\PhpCsFixerConfig;

$config = new StrictConfig();
$config->getFinder()->in(__DIR__ . '/src');

$config->setCacheFile(__DIR__ . '/.php_cs.cache');

return $config;
