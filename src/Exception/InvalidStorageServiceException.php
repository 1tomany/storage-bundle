<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class InvalidStorageServiceException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $service)
    {
        parent::__construct(sprintf('The storage service "%s" is invalid.', $service), 500);
    }
}
