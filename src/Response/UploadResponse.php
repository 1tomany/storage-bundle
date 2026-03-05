<?php

namespace OneToMany\StorageBundle\Response;

final readonly class UploadResponse
{
    public function __construct(private string $url)
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
