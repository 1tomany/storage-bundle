<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class InvalidStorageServiceException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $service, ?\Throwable $previous = null, int $code = 0)
    {
        parent::__construct(sprintf('The storage service "%s" is invalid.', $service), $code, $previous);
    }
}
