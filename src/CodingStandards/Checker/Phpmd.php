<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Config;
use Opositatest\CodingStandards\Exception\PhpmdError;
use Opositatest\CodingStandards\Exception\CheckFailException;
use Opositatest\CodingStandards\Tools\Files;

final class Phpmd implements Checker
{
    private array $config;

    public function __construct()
    {
        $this->config = Config::loadChecker('phpmd');
    }

    public function check(array $files): void
    {
        $errors = [];
        foreach ($files as $file) {
            if (false === Files::exist($file, $this->config['paths'])) {
                continue;
            }

            $return = null;
            $output = [];
            $command = 'vendor/phpmd/phpmd/src/bin/phpmd ' . $file . ' json ' . Config::rootDir() . '/phpmd_ruleset.xml';
            exec($command, $output, $return);

            if (0 !== $return) {
                $output = json_decode(implode("\n", $output));
                foreach ($output->files as $outputFile) {
                    $errors[] = new PhpmdError($file, $outputFile->violations);
                }
            }
        }

        if (0 < count($errors)) {
            throw CheckFailException::withErrors(
                'PHPMD', sprintf('There are %s errors to solve', count($errors)), $errors
            );
        }
    }
}
