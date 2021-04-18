<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

class PhpmdError
{
    private string $file;
    private array $violations;

    public function __construct(string $file, array $violations)
    {
        $this->file = $file;
        $this->violations = $violations;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function violations(): array
    {
        return $this->violations;
    }
}
