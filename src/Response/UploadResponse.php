<?php

namespace OneToMany\StorageBundle\Response;

final readonly class UploadResponse
{
    /**
     * @param non-empty-string $url
     */
    public function __construct(
        private string $url,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
