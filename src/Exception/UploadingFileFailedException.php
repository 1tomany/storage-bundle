<?php

namespace OneToMany\StorageBundle\Exception;

final class UploadingFileFailedException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?\Throwable $previous = null, int $code = 0)
    {
        parent::__construct('An error occurred when attempting to upload the file.', $code, $previous);
    }
}
