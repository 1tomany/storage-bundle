<?php

namespace OneToMany\StorageBundle\Exception;

use function sprintf;

final class LocalFileNotReadableException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $filePath, ?\Throwable $previous = null, int $code = 0)
    {
        parent::__construct(sprintf('The file "%s" is not readable and could not be uploaded.', $filePath), $code, $previous);
    }
}
