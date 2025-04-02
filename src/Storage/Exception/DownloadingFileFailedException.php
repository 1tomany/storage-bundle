<?php

namespace App\File\Service\Storage\Exception;

use App\File\Contract\Exception\ExceptionInterface;
use OneToMany\RichBundle\Exception\Attribute\HasUserMessage;

#[HasUserMessage]
final class DownloadingFileFailedException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?string $name, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('An error occurred when attempting to download the file named "%s".', $name), 500, $previous);
    }
}
