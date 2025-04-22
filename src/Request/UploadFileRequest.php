<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\DataUri\SmartFile;

final readonly class UploadFileRequest
{
    public function __construct(
        public SmartFile $file,
        public bool $isPublic = true,
    ) {
    }

    public static function public(SmartFile $file): self
    {
        return new self($file, true);
    }

    public static function private(SmartFile $file): self
    {
        return new self($file, false);
    }
}
