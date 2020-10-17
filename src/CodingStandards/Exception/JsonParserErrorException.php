<?php

declare(strict_types=1);

namespace Opositatest\CodingStandards\Exception;

class JsonParserErrorException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'The format of the JSON file is invalid. Please validate ' .
            'the syntax with for example "https://jsonlint.com/"'
        );
    }
}
