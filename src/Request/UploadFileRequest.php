<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\DataUri\SmartFile;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $filePath,
        public string $mediaType,
        public string $remoteKey,
        public bool $isPublic = true,
    ) {
    }

    // @phpstan-ignore-next-line
    public static function fromSmartFile(SmartFile $file, bool $isPublic = true): self
    {
        // @phpstan-ignore-next-line
        return new self($file->filePath, $file->mediaType, $file->remoteKey, $isPublic);
    }

    public static function public(string $filePath, string $mediaType, string $remoteKey): self
    {
        return new self($filePath, $mediaType, $remoteKey, true);
    }

    public static function private(string $filePath, string $mediaType, string $remoteKey): self
    {
        return new self($filePath, $mediaType, $remoteKey, false);
    }
}
