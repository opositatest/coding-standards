<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Tools\Files;

final class PhpCsFixer implements Checker
{
    private const CONFIG_FILE = '.php_cs';

    public static function check(array $files, array $config): void
    {
        foreach ($files as $file) {
            if (false === Files::exist($file, $config['phpcsfixer_path'])
                && false === Files::exist($file, $config['phpcsfixer_test_path'])
            ) {
                continue;
            }

            self::execute($file, $config);
        }
    }

    private static function execute($file, array $config): void
    {
        // Exec PHP function is used because php-cs-fixer uses Symfony Process component inside
        // Process fails when is launched from another Process
        $commandLine = [
            'php',
            'vendor/friendsofphp/php-cs-fixer/php-cs-fixer',
            'fix',
            $file,
            '--config=' . self::location($config) . '/' . self::CONFIG_FILE,
            '2> /dev/null',
        ];
        exec(implode(' ', $commandLine));
    }

    public static function createConfigFile(array $config): void
    {
        $file = file_get_contents(__DIR__ . '/../' . self::CONFIG_FILE . '.dist');

        $file = str_replace(
            '$$CHANGE-FOR-PHPCSFIXER-PATH$$',
            $config['phpcsfixer_path'],
            $file
        );

        try {
            file_put_contents(self::location($config) . '/' . self::CONFIG_FILE, $file);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the creating process: \n%s\n", $exception->getMessage());
        }
    }

    private static function location(array $config): string
    {
        return $config['root_directory'] . '/' . $config['phpcsfixer_file_location'];
    }
}
