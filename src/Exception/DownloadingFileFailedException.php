<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class DownloadingFileFailedException extends RuntimeException
{
    public function __construct(?string $name, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('An error occurred when attempting to download the file "%s".', $name), previous: $previous);
    }
}
