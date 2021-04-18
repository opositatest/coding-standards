<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

interface Checker
{
    public function check(array $files): void;
}
