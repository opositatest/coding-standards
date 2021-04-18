<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Symfony\Component\Yaml\Yaml;

final class Config
{
    public static function load(): array
    {
        return Yaml::parse(file_get_contents(self::rootDir() . '/.opos_cs.yml'));
    }

    public static function loadChecker(string $id): array
    {
        $config = self::load();

        return $config['checker'][$id];
    }

    public static function rootDir() : string
    {
        return __DIR__ . '/../../../../..';
    }

    public static function csRootDir() : string
    {
        return __DIR__ . '/../..';
    }
}
