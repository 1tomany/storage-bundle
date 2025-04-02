<?php

namespace App\File\Service\Storage\Exception;

use App\File\Contract\Exception\ExceptionInterface;

final class LocalFileNotReadableException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf('The file "%s" is not readable and could not be uploaded.', $filePath), 500);
    }
}
