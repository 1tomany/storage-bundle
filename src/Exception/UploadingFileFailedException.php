<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class UploadingFileFailedException extends RuntimeException
{
    public function __construct(string $key, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Failed to upload the file "%s".', $key), previous: $previous);
    }
}
