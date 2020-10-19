<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Git;

final class Git
{
    public static function committedFiles(): array
    {
        $output = [];
        $rc = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $rc);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if (0 === $rc) {
            $against = 'HEAD';
        }

        exec("git diff-index --cached --name-status $against | egrep '^(A|M)' | awk '{print $2;}'", $output);

        return $output;
    }

    public static function addFiles(array $files, $rootDirectory = null): array
    {
        foreach ($files as $file) {
            if (false === file_exists($file)) {
                continue;
            }

            exec(sprintf('git add %s', $file));
        }

        return $files;
    }
}
