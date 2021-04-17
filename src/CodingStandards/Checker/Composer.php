<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Exception\CheckFailException;

final class Composer implements Checker
{
    public static function check(array $files, array $config): void
    {
        $composerJsonDetected = false;
        $composerLockDetected = false;

        foreach ($files as $file) {
            if ('composer.json' === $file) {
                $composerJsonDetected = true;
            }

            if ('composer.lock' === $file) {
                $composerLockDetected = true;
            }
        }

        if ($composerJsonDetected && !$composerLockDetected) {
            throw new CheckFailException('Composer', 'composer.lock must be committed if composer.json is modified!');
        }
    }
}
