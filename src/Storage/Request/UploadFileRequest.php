<?php

namespace OneToMany\StorageBundle\Storage\Request;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $path,
        public string $key,
        public string $media,
        public bool $isPublic = true,
    ) {
    }

    public static function public(string $path, string $key, string $media): self
    {
        return new self($path, $key, $media, true);
    }

    public static function private(string $path, string $key, string $media): self
    {
        return new self($path, $key, $media, false);
    }
}
