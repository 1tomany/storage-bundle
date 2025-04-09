<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class LocalFileNotReadableException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $filePath, ?\Throwable $previous = null, int $code = 0)
    {
        parent::__construct(sprintf('The file "%s" could not be uploaded because it is not readable or does not exist.', $filePath), $code, $previous);
    }
}
