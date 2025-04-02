<?php

namespace OneToMany\StorageBundle\Storage\Request;

final readonly class DownloadFileRequest
{
    public function __construct(public string $key)
    {
    }
}
