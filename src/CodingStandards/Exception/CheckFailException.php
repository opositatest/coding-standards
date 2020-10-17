<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

class CheckFailException extends \Exception
{
    public function __construct($checkName, $message = '')
    {
        parent::__construct(sprintf('Check fails during the %s. %s', $checkName, $message));
    }
}
