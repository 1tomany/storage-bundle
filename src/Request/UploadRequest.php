<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Request\Trait\ValidatePathTrait;

use function strtolower;
use function trim;

class UploadRequest
{
    use ValidatePathTrait;

    public function __construct(
        private string $path,
        private string $format,
        private string $key,
        private bool $isPublic = true,
    ) {
        $this->atPath($path);
        $this->asFormat($format);
        $this->usingKey($key);
    }

    public static function public(string $path, string $format, string $key): self
    {
        return new self($path, $format, $key)->markAsPublic();
    }

    public static function private(string $path, string $format, string $key): self
    {
        return new self($path, $format, $key)->markAsPrivate();
    }

    public function atPath(string $path): static
    {
        $this->path = $this->validatePath($path);

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function asFormat(string $format): static
    {
        $this->format = strtolower(trim($format));

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function usingKey(string $key): static
    {
        $this->key = trim($key);

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function markAsPublic(): static
    {
        $this->isPublic = true;

        return $this;
    }

    public function markAsPrivate(): static
    {
        $this->isPublic = false;

        return $this;
    }
}
