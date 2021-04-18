<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Config;
use Opositatest\CodingStandards\Exception\CheckFailException;
use Opositatest\CodingStandards\Tools\Files;

final class Phpmd extends Checker
{
    protected const CHECKER = 'phpmd';

    public function check(array $files): void
    {
        if (false === $this->isEnabled()) {
            return;
        }

        $this->output->writeln('<info>Checking code mess with PHPMD...</info>');
        $errorsFound = false;

        foreach ($files as $file) {
            if (false === Files::exist($file, $this->config['paths'])) {
                continue;
            }

            $return = null;
            $output = [];
            $command = 'vendor/phpmd/phpmd/src/bin/phpmd ' . $file . ' json ' . Config::rootDir() . '/phpmd_ruleset.xml';
            exec($command, $output, $return);

            if (0 !== $return) {
                $errorsFound = true;
                $output = json_decode(implode("\n", $output));
                foreach ($output->files as $outputFile) {

                    $this->output->writeln(sprintf('File: %s', $file));
                    $this->output->writeln(str_pad('', mb_strlen(sprintf('File: %s', $file)), '='));
                    foreach ($outputFile->violations as $violation) {
                        $this->output->writeln(sprintf(
                            'Priority: %s | Lines: %s to %s | %s (%s in "%s")',
                            $violation->priority,
                            str_pad((string) $violation->beginLine, 4, ' ', STR_PAD_LEFT),
                            str_pad((string) $violation->endLine, 4, ' ', STR_PAD_LEFT),
                            $violation->description,
                            $violation->rule,
                            $violation->ruleSet
                        ));
                    }
                }
            }
        }

        if (true === $errorsFound) {
            throw new CheckFailException('PHPMD');
        }
    }
}
