<?php

namespace OneToMany\StorageBundle\Request;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $filePath,
        public string $remoteKey,
        public ?string $contentType = null,
        public bool $isPublic = true,
    ) {
    }

    public static function public(
        string $filePath,
        string $remoteKey,
        ?string $contentType = null,
    ): self {
        return new self($filePath, $remoteKey, $contentType, true);
    }

    public static function private(
        string $filePath,
        string $remoteKey,
        ?string $contentType = null,
    ): self {
        return new self($filePath, $remoteKey, $contentType, false);
    }
}
