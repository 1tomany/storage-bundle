<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\DataUri\SmartFile;

final readonly class UploadFileRequest
{
    public function __construct(
        public string $path,
        public string $type,
        public string $key,
        public bool $isPublic = true,
    ) {
    }

    public static function createFromSmartFile(SmartFile $file, bool $isPublic = true): self // @phpstan-ignore-line
    {
        return new self($file->path, $file->type, $file->key, $isPublic); // @phpstan-ignore-line
    }

    public static function public(string $path, string $type, string $key): self
    {
        return new self($path, $type, $key, true);
    }

    public static function private(string $path, string $type, string $key): self
    {
        return new self($path, $type, $key, false);
    }
}
