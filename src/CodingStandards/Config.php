<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Symfony\Component\Yaml\Yaml;

final class Config
{
    public static function load(): array
    {
        $rootDirectory = realpath(__DIR__ . '/../../../../../');
        $config = Yaml::parse(file_get_contents($rootDirectory . '/.opos_cs.yml'))['parameters'];
        $config['root_directory'] = $rootDirectory;

        return $config;
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
