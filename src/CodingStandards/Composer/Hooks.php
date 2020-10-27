<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Composer;

use Opositatest\CodingStandards\Application;
use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Opositatest\CodingStandards\Config;
use Symfony\Component\Filesystem\Filesystem;

final class Hooks
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
        Config::copyFileIfNotExists('.opos_cs.yml.dist');
    }

    public static function addFiles() : void
    {
        $app = new Application();
        $enabled = $app->parameters()['enabled'] ?? [];

        !in_array('phpcsfixer', $enabled, true) ?: PhpCsFixer::file($app->parameters());
        !in_array('phpmd', $enabled, true) ?: Config::copyFileIfNotExists('phpmd_ruleset.xml');
    }
}
