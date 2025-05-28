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

    public static function public(string $filePath, string $contentType, string $remoteKey): self
    {
        return new self($filePath, $contentType, $remoteKey, true);
    }

    public static function private(string $filePath, string $contentType, string $remoteKey): self
    {
        return new self($filePath, $contentType, $remoteKey, false);
    }
}
