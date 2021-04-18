<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards;

use Opositatest\CodingStandards\Checker\Composer;
use Opositatest\CodingStandards\Checker\PhpCsFixer;
use Opositatest\CodingStandards\Checker\Phpmd;
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

        (new Composer())->check($files);

        (new PhpCsFixer())->check($files);

        (new Phpmd())->check($files);

        Git::addFiles($files);
        $output->writeln('<info>Nice commit!</info>');
    }
}
