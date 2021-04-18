<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Opositatest\CodingStandards\Checker\Composer;
use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Opositatest\CodingStandards\Checker\Phpmd;
use Opositatest\CodingStandards\Exception\CheckFailException;
use Opositatest\CodingStandards\Tools\Git;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Application extends BaseApplication
{
    private const APP_NAME = 'Opositatest Coding Standards';

    private array $config;

    public function __construct()
    {
        parent::__construct(self::APP_NAME);
        $this->config = Config::load();
    }

    public function doRun(InputInterface $input, OutputInterface $output): void
    {
        if (true !== $this->config['global']['enabled']) {
            return;
        }

        $output->writeln(sprintf('<fg=white;options=bold;bg=blue>%s</fg=white;options=bold;bg=blue>', self::APP_NAME));
        $output->writeln('<info>Fetching files...</info>');
        $files = Git::committedFiles();

        if (true === $this->config['checker']['composer']['enabled']) {
            $output->writeln('<info>Check composer</info>');
            (new Composer())->check($files);
        }

        if (true === $this->config['checker']['phpcsfixer']['enabled']) {
            $output->writeln('<info>Fixing PHP code style with PHP-CS-Fixer</info>');
            (new PhpCsFixer())->check($files);
        }

        if (true === $this->config['checker']['phpmd']['enabled']) {
            $output->writeln('<info>Checking code mess with PHPMD</info>');
            try {
                (new Phpmd())->check($files);
            } catch (CheckFailException $exception) {
                foreach ($exception->errors() as $error) {
                    $output->writeln(sprintf('File: %s', $error->file()));
                    $output->writeln(str_pad('', mb_strlen(sprintf('File: %s', $error->file())), '='));
                    foreach ($error->violations() as $violation) {
                        $output->writeln(sprintf(
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

                throw $exception;
            }
        }

        Git::addFiles($files);
        $output->writeln('<info>Nice commit man!</info>');
    }
}
