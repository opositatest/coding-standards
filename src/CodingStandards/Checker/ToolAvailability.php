<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Exception\ToolUnavailableException;
use Symfony\Component\Process\Process;

trait ToolAvailability
{
    protected static function isAvailable($tool): void
    {
        $process = new Process(sprintf('%s -v', $tool));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ToolUnavailableException($tool);
        }
    }
}
