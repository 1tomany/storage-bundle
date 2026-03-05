<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Request\Trait\ValidatePathTrait;

use function basename;
use function strtolower;
use function trim;

class UploadRequest
{
    use ValidatePathTrait;

    /**
     * @var non-empty-string
     */
    private string $path;

    /**
     * @var non-empty-lowercase-string
     */
    private string $format = self::DEFAULT_FORMAT;

    /**
     * @var non-empty-string
     */
    private string $key = self::DEFAULT_KEY;

    public const string DEFAULT_FORMAT = 'application/octet-stream';
    public const string DEFAULT_KEY = '__unknown_key__';

    public function __construct(
        string $path,
        ?string $format = null,
        ?string $key = null,
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

    /**
     * @return non-empty-string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function asFormat(?string $format): static
    {
        $this->format = strtolower(trim($format ?? '')) ?: 'application/octet-stream';

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function usingKey(?string $key): static
    {
        if (!$key = trim($key ?? '')) {
            $key = basename($this->path);
        }

        $this->key = $key ?: self::DEFAULT_KEY;

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
