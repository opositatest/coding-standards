<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Config;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Checker
{
    protected const CHECKER = 'id';

    protected OutputInterface $output;
    protected array $config;

    public function __construct(?OutputInterface $output = null)
    {
        $this->config = Config::loadChecker(static::CHECKER);
        $this->output = null === $output ? new ConsoleOutput() : $output;
    }

    public function check(array $files): void {}

    public function isEnabled(): bool
    {
        return true === $this->config['enabled'];
    }
}
