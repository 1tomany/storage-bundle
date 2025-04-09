<?php

namespace OneToMany\StorageBundle\Request;

use function implode;
use function rtrim;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $filePath,
        public string $remoteKey,
        public ?string $contentType = null,
        public bool $isPublic = true,
        public ?string $customUrl = null,
    ) {
    }

    public static function public(
        string $filePath,
        string $remoteKey,
        ?string $contentType = null,
        ?string $customUrl = null,
    ): self {
        return new self($filePath, $remoteKey, $contentType, true, $customUrl);
    }

    public static function private(
        string $filePath,
        string $remoteKey,
        ?string $contentType = null,
        ?string $customUrl = null,
    ): self {
        return new self($filePath, $remoteKey, $contentType, false, $customUrl);
    }

    public function getUrl(string $canonicalUrl): string
    {
        $customUrl = rtrim((string) $this->customUrl, '/');

        if (empty($customUrl)) {
            return $canonicalUrl;
        }

        return implode('/', [$customUrl, $this->remoteKey]);
    }
}
