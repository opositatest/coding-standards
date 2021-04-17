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
        $output->writeln(sprintf('<fg=white;options=bold;bg=blue>%s</fg=white;options=bold;bg=blue>', self::APP_NAME));
        $output->writeln('<info>Fetching files...</info>');
        $files = Git::committedFiles();

        if (in_array('composer', $this->config['enabled'], true)) {
            $output->writeln('<info>Check composer</info>');
            Composer::check($files, []);
        }

        if (in_array('phpcsfixer', $this->config['enabled'], true)) {
            $output->writeln('<info>Fixing PHP code style with PHP-CS-Fixer</info>');
            PhpCsFixer::check($files, $this->config);
        }

        if (in_array('phpmd', $this->config['enabled'], true)) {
            $output->writeln('<info>Checking code mess with PHPMD</info>');
            $phpMdErrors = Phpmd::check($files, $this->config);
            if (count($phpMdErrors) > 0) {
                foreach ($phpMdErrors as $error) {
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
                throw new CheckFailException('PHPMD');
            }
        }

        Git::addFiles($files);
        $output->writeln('<info>Nice commit man!</info>');
    }
}
