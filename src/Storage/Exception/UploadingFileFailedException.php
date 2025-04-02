<?php

namespace OneToMany\StorageBundle\Storage\Exception;

final class UploadingFileFailedException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('An error occurred when attempting to upload the file.', 500, $previous);
    }
}
