<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Opositatest\CodingStandards\Checker\Composer;
use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Opositatest\CodingStandards\Checker\Phpmd;
use Opositatest\CodingStandards\Exception\CheckFailException;
use Opositatest\CodingStandards\Git\Git;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

final class Application extends BaseApplication
{
    private const APP_NAME = 'Opositatest Coding Standards';

    private string $name;
    private array $parameters;

    public function __construct()
    {
        parent::__construct(self::APP_NAME);

        $rootDirectory = realpath(__DIR__ . '/../../../../../');
        $this->parameters = Yaml::parse(file_get_contents($rootDirectory . '/.opos_cs.yml'))['parameters'];
        $this->parameters['root_directory'] = $rootDirectory;
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<fg=white;options=bold;bg=red>%s</fg=white;options=bold;bg=red>', $this->name));
        $output->writeln('<info>Fetching files...</info>');
        $files = Git::committedFiles();

        $output->writeln('<info>Check composer</info>');
        Composer::check($files);

        if (in_array('phpcsfixer', $this->parameters['enabled'], true)) {
            $output->writeln('<info>Fixing PHP code style with PHP-CS-Fixer</info>');
            PhpCsFixer::check($files, $this->parameters);
        }

        if (in_array('phpmd', $this->parameters['enabled'], true)) {
            $output->writeln('<info>Checking code mess with PHPMD</info>');
            $phpmdResult = Phpmd::check($files, $this->parameters);
            if (count($phpmdResult) > 0) {
                foreach ($phpmdResult as $error) {
                    $output->writeln($error->output());
                }
                throw new CheckFailException('PHPMD');
            }
        }

        Git::addFiles($files, $this->parameters['root_directory']);
        $output->writeln('<info>Nice commit man!</info>');
    }

    public function parameters()
    {
        return $this->parameters;
    }
}
