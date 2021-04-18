<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

use Exception;

class CheckFailException extends Exception
{
    public function __construct(string $checkName)
    {
        parent::__construct(sprintf('Check failed during the %s check.', $checkName));
    }
}
