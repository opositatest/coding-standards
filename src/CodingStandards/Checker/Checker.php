<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

interface Checker
{
    public static function check(array $files = [], array $parameters = null);
}
