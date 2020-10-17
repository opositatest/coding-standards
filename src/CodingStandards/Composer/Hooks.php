<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Composer;

use Opositatest\CodingStandards\Application;
use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Symfony\Component\Filesystem\Filesystem;

final class Hooks
{
    public static function addHooks() : void
    {
        $hooksDirectory = self::rootDir() . '/.git/hooks';
        $fileSystem = new Filesystem();

        try {
            if ($fileSystem->exists($hooksDirectory)) {
                $fileSystem->remove($hooksDirectory);
            }
            $fileSystem->symlink(__DIR__ . '/../Hooks', $hooksDirectory, true);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the symlink process: \n%s\n", $exception->getMessage());
        }
    }

    public static function buildDistFile() : void
    {
        $distFile = self::rootDir() . '/.opos_cs.yml.dist';
        $fileSystem = new Filesystem();

        try {
            if ($fileSystem->exists($distFile)) {
                return;
            }

            $fileSystem->copy(self::csRootDir() . '/.opos_cs.yml.dist', $distFile);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the touch process: \n%s\n", $exception->getMessage());
        }
    }

    public static function addFiles() : void
    {
        $app = new Application();
        $enabled = $app->parameters()['enabled'] ?? [];

        !in_array('phpcsfixer', $enabled, true) ?: PhpCsFixer::file($app->parameters());
    }

    private static function rootDir() : string
    {
        return __DIR__ . '/../../../../../..';
    }

    private static function csRootDir() : string
    {
        return __DIR__ . '/../../..';
    }
}
