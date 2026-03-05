<?php

namespace OneToMany\StorageBundle\Response;

final readonly class DownloadResponse
{
    public function __construct(private string $path)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
