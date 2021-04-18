<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

use Opositatest\CodingStandards\Config;
use Opositatest\CodingStandards\Tools\Files;

final class PhpCsFixer implements Checker
{
    private const CONFIG_FILE = '.php_cs';

    private array $config;

    public function __construct()
    {
        $this->config = Config::loadChecker('phpcsfixer');
    }

    public function check(array $files): void
    {
        foreach ($files as $file) {
            if (false === Files::exist($file, $this->config['paths'])) {
                continue;
            }

            self::execute($file);
        }
    }

    private function execute($file): void
    {
        // Exec PHP function is used because php-cs-fixer uses Symfony Process component inside
        // Process fails when is launched from another Process
        $commandLine = [
            'php',
            'vendor/friendsofphp/php-cs-fixer/php-cs-fixer',
            'fix',
            $file,
            '--config=' . $this->configPath() . '/' . self::CONFIG_FILE,
            '2> /dev/null',
        ];
        exec(implode(' ', $commandLine));
    }

    public function createConfigFile(): void
    {
        $file = file_get_contents(__DIR__ . '/../' . self::CONFIG_FILE . '.dist');

        $file = str_replace(
            '$$CHANGE-FOR-PHPCSFIXER-PATH$$',
            sprintf('[\'%s\']', implode('\', \'', $this->config['paths'])),
            $file
        );

        try {
            file_put_contents($this->configPath() . '/' . self::CONFIG_FILE, $file);
        } catch (\Exception $exception) {
            echo sprintf("Something wrong happens during the creating process: \n%s\n", $exception->getMessage());
        }
    }

    private function configPath(): string
    {
        return Config::rootDir() . '/' . $this->config['config_path'];
    }
}
