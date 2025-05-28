<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\DataUri\SmartFile;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $filePath,
        public string $contentType,
        public string $remoteKey,
        public bool $isPublic = true,
    ) {
    }

    public static function createFromSmartFile(SmartFile $file, bool $isPublic = true): self // @phpstan-ignore-line
    {
        return new self($file->filePath, $file->contentType, $file->remoteKey, $isPublic); // @phpstan-ignore-line
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
