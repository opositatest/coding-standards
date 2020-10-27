<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Symfony\Component\Filesystem\Filesystem;

final class Config
{
    public static function copyFileIfNotExists(string $filename): void
    {
        $destination = self::rootDir() . '/' . $filename;
        $fileSystem = new Filesystem();

        try {
            if ($fileSystem->exists($destination)) {
                return;
            }

            $fileSystem->copy(self::csRootDir() . '/' . $filename, $destination);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the touch process: \n%s\n", $exception->getMessage());
        }
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
