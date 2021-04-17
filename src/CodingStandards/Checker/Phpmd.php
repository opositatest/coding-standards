<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Config;
use Opositatest\CodingStandards\Error\Error;
use Opositatest\CodingStandards\Tools\Files;

final class Phpmd implements Checker
{
    public static function check(array $files, array $config): array
    {
        $errors = [];
        foreach ($files as $file) {
            if (false === Files::exist($file, $config['phpmd_path'])) {
                continue;
            }

            $return = null;
            $output = [];
            $command = 'vendor/phpmd/phpmd/src/bin/phpmd ' . $file . ' json ' . Config::rootDir() . '/phpmd_ruleset.xml';
            exec($command, $output, $return);

            if (0 !== $return) {
                $output = json_decode(implode("\n", $output));
                foreach ($output->files as $outputFile) {
                    $errors[] = new Error($file, $outputFile->violations);
                }
            }
        }

        return $errors;
    }
}
