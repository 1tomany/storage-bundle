<?php

namespace OneToMany\StorageBundle\Exception;

final class DownloadingFileFailedException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?string $name, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('An error occurred when attempting to download the file "%s".', $name), 500, $previous);
    }
}
