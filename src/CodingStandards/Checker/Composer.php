<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Exception\CheckFailException;

final class Composer extends Checker
{
    protected const CHECKER = 'composer';

    public function check(array $files): void
    {
        if (false === $this->isEnabled()) {
            return;
        }

        $this->output->writeln('<info>Checking composer...</info>');

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
            $this->output->writeln('composer.lock must be committed if composer.json is modified!');

            throw new CheckFailException('Composer');
        }
    }
}
