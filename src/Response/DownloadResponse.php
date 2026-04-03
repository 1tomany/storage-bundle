<?php

namespace OneToMany\StorageBundle\Response;

final readonly class DownloadResponse
{
    /**
     * @param non-empty-string $path
     */
    public function __construct(
        private string $path,
    )
    {
    }

    /**
     * @return non-empty-string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
