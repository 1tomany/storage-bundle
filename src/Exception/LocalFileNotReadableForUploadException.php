<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class LocalFileNotReadableForUploadException extends RuntimeException
{
    public function __construct(string $path, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Failed to upload the file "%s" because it does not exist.', $path), previous: $previous);
    }
}
