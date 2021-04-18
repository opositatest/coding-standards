<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

class CheckFailException extends \Exception
{
    private array $errors;

    public function __construct(string $checkName, string $message)
    {
        parent::__construct(sprintf('Check fails during the %s. %s', $checkName, $message));
    }

    public static function withErrors(string $checkName, string $message, array $errors): self
    {
        $exception = new self($checkName, $message);
        $exception->errors = $errors;

        return $exception;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
