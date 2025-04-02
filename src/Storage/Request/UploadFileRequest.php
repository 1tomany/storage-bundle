<?php

namespace OneToMany\StorageBundle\Storage\Request;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $path,
        public string $key,
        public ?string $type = null,
        public bool $isPublic = true,
    ) {
    }

    public static function public(string $path, string $key, ?string $type = null): self
    {
        return new self($path, $key, $type, true);
    }

    public static function private(string $path, string $key, ?string $type = null): self
    {
        return new self($path, $key, $type, false);
    }
}
