<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Tools;

use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Opositatest\CodingStandards\Config;
use Symfony\Component\Filesystem\Filesystem;

final class Files
{
    public static function addHooks() : void
    {
        $hooksDirectory = Config::rootDir() . '/.git/hooks';
        $fileSystem = new Filesystem();

        try {
            if ($fileSystem->exists($hooksDirectory)) {
                $fileSystem->remove($hooksDirectory);
            }
            $fileSystem->symlink(
                '../vendor/opositatest/coding-standards/src/CodingStandards/Hooks',
                $hooksDirectory,
                true
            );
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the symlink process: \n%s\n", $exception->getMessage());
        }
    }

    public static function buildDistFile() : void
    {
        self::copyFileIfNotExists(
            sprintf('%s/.opos_cs.yml.dist', Config::csRootDir()),
            sprintf('%s/.opos_cs.yml.dist', Config::rootDir())
        );
    }

    public static function addFiles() : void
    {
        (new PhpCsFixer())->createConfigFile();
        self::copyFileIfNotExists(
            sprintf('%s/phpmd_ruleset.xml', Config::csRootDir()),
            sprintf('%s/phpmd_ruleset.xml', Config::rootDir())
        );
    }

    public static function exist(string $file, array $paths, string $fileType = 'php'): bool
    {
        foreach ($paths as $path) {
            if (0 !== preg_match('/^' . str_replace('/', '\/', $path) . '\/(.*)(\.' . $fileType . ')$/', $file)) {
                return true;
            }
        }

        return false;
    }

    private static function copyFileIfNotExists(string $source, string $destination): void
    {
        $fileSystem = new Filesystem();

        try {
            if ($fileSystem->exists($destination)) {
                return;
            }

            $fileSystem->copy($source, $destination);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the touch process: \n%s\n", $exception->getMessage());
        }
    }
}
