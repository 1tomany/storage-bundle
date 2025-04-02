<?php

namespace App\File\Service\Storage\Request;

final readonly class DownloadFileRequest
{
    public function __construct(public string $key)
    {
    }
}
