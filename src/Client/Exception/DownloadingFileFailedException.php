<?php

namespace OneToMany\StorageBundle\Client\Exception;

use OneToMany\StorageBundle\Exception\RuntimeException;

use function sprintf;

final class DownloadingFileFailedException extends RuntimeException
{
    public function __construct(string $key, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Failed to download the file "%s".', $key), previous: $previous);
    }
}
