<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Error\Error;
use Symfony\Component\Process\Process;

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

            $process = new Process(
                'vendor/phpmd/phpmd/src/bin/phpmd ' . $file . ' text ' . implode(',', $parameters['phpmd_rules'])
            );
            $process->setWorkingDirectory($parameters['root_directory']);
            $process->run();

            if (!$process->isSuccessful()) {
                $errors[] = new Error(
                    $file,
                    sprintf('<error>%s</error>', trim($process->getErrorOutput())),
                    sprintf('<error>%s</error>', trim($process->getOutput()))
                );
            }
        }

        return $errors;
    }
}
