<?php

namespace App\File\Service\Storage\Exception;

use App\File\Contract\Exception\ExceptionInterface;
use OneToMany\RichBundle\Exception\Attribute\HasUserMessage;

#[HasUserMessage]
final class UploadingFileFailedException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('An error occurred when attempting to upload the file.', 500, $previous);
    }
}
