<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

class ToolUnavailableException extends \Exception
{
    public function __construct($toolName)
    {
        parent::__construct(sprintf('%s is unavailable so, you have to consider to install it', $toolName));
    }
}
