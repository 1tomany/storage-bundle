<?php

namespace OneToMany\StorageBundle\Storage\Exception;

final class InvalidStorageServiceException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $service)
    {
        parent::__construct(sprintf('The storage service "%s" is invalid.', $service), 500);
    }
}
