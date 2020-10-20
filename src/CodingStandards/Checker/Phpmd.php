<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Error\Error;

final class Phpmd implements Checker
{
    use FileFinder;

    public static function check(array $files = [], array $parameters = null): array
    {
        $errors = [];
        foreach ($files as $file) {
            if (false === self::exist($file, $parameters['phpmd_path'], 'php')) {
                continue;
            }

            $return = null;
            $output = [];
            $command = 'vendor/phpmd/phpmd/src/bin/phpmd ' . $file . ' json ' . implode(',', $parameters['phpmd_rules']);
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
