<?php

namespace App\File\Service\Storage\Exception;

use App\File\Contract\Exception\ExceptionInterface;

final class InvalidStorageServiceException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $service)
    {
        parent::__construct(sprintf('The storage service "%s" is invalid.', $service), 500);
    }
}
