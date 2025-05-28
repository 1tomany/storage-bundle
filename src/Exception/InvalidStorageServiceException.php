<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class InvalidStorageServiceException extends RuntimeException
{
    public function __construct(string $service, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('The storage service "%s" is invalid.', $service), previous: $previous);
    }
}
