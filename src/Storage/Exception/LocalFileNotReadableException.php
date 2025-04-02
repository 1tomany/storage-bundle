<?php

namespace OneToMany\StorageBundle\Storage\Exception;

final class LocalFileNotReadableException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $filePath)
    {
        parent::__construct(sprintf('The file "%s" is not readable and could not be uploaded.', $filePath), 500);
    }
}
