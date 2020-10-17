<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Error;

class Error
{
    private string $file;
    private string $error;
    private string $output;

    public function __construct(string $file, string $error, string $output)
    {
        $this->file = $file;
        $this->error = $error;
        $this->output = $output;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function error(): string
    {
        return $this->error;
    }

    public function output(): string
    {
        return $this->output;
    }
}
