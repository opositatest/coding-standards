<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

final class PhpCsFixer implements Checker
{
    use FileFinder;

    public static function check(array $files = [], array $parameters = null): void
    {
        foreach ($files as $file) {
            if (false === self::exist($file, $parameters['phpcsfixer_path'], 'php')
                && false === self::exist($file, $parameters['phpcsfixer_test_path'], 'php')
            ) {
                continue;
            }

            self::execute($file, $parameters);
        }
    }

    public static function file(array $parameters)
    {
        self::phpCsConfigFile($parameters);
    }

    private static function execute($file, array $parameters, $checkFile = '.php_cs')
    {
        // Exec PHP function is used because php-cs-fixer uses Symfony Process component inside
        // Process fails when is launched from another Process
        $commandLine = [
            'php',
            'vendor/friendsofphp/php-cs-fixer/php-cs-fixer',
            'fix',
            $file,
            '--config=' . self::location($parameters) . '/' . $checkFile,
            '2> /dev/null',
        ];
        exec(implode(' ', $commandLine));
    }

    private static function phpCsConfigFile(array $parameters): void
    {
        self::configFile('.php_cs', $parameters);
    }

    private static function configFile($fileName, array $parameters): void
    {
        $file = file_get_contents(__DIR__ . '/../' . $fileName . '.dist');

        $file = str_replace(
            '$$CHANGE-FOR-PHPCSFIXER-PATH$$',
            $parameters['phpcsfixer_path'],
            $file
        );

        try {
            file_put_contents(self::location($parameters) . '/' . $fileName, $file);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the creating process: \n%s\n", $exception->getMessage());
        }
    }

    private static function location(array $parameters): string
    {
        return $parameters['root_directory'] . '/' . $parameters['phpcsfixer_file_location'];
    }
}
