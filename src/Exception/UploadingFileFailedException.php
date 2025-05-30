<?php

namespace OneToMany\StorageBundle\Exception;

final class UploadingFileFailedException extends RuntimeException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('An error occurred when attempting to upload the file.', previous: $previous);
    }
}
