<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class LocalFileNotReadableForUploadException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $filePath, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('The file "%s" could not be uploaded because it is not readable or does not exist.', $filePath), previous: $previous);
    }
}
