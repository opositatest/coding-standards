<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Checker;

trait FileFinder
{
    protected static function exist($file, $path, $fileType = 'php')
    {
        return 0 !== preg_match('/^' . str_replace('/', '\/', $path) . '\/(.*)(\.' . $fileType . ')$/', $file);
    }
}
