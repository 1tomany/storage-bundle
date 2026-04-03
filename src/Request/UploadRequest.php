<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\RequestInterface;
use OneToMany\StorageBundle\Request\Trait\ValidatePathTrait;

use function basename;
use function bin2hex;
use function random_bytes;
use function strtolower;
use function trim;

class UploadRequest implements RequestInterface
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
    private string $key;

    /**
     * The default file format.
     *
     * @var non-empty-lowercase-string
     */
    public const string DEFAULT_FORMAT = 'application/octet-stream';

    public function __construct(
        string $path,
        ?string $format = null,
        ?string $key = null,
        private bool $isPublic = true,
    ) {
        $this->fromPath($path);
        $this->withFormat($format);
        $this->withKey($key);
    }

    public static function public(string $path, string $format, string $key): self
    {
        return new self($path, $format, $key)->markAsPublic();
    }

    public static function private(string $path, string $format, string $key): self
    {
        return new self($path, $format, $key)->markAsPrivate();
    }

    public function fromPath(string $path): static
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

    public function withFormat(?string $format): static
    {
        $this->format = strtolower(trim((string) $format)) ?: self::DEFAULT_FORMAT;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function withKey(?string $key): static
    {
        if (!$key = trim((string) $key)) {
            $key = basename($this->path);
        }

        $this->key = $key ?: self::FILE_PREFIX.bin2hex(random_bytes(6));

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
