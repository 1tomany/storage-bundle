<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function sprintf;
use function strtolower;
use function trim;

class UploadFileRequest implements UploadFileRequestInterface
{
    /**
     * @var non-empty-string
     */
    private string $path;

    /**
     * @var non-empty-string
     */
    private string $mimeType;

    /**
     * @var non-empty-string
     */
    private string $key;
    private bool $isPublic = true;

    public function __construct(
        string $path,
        string $mimeType,
        string $key,
    ) {
        $this->setPath($path);
        $this->setMimeType($mimeType);
        $this->setKey($key);
    }

    public static function public(string $path, string $mimeType, string $key): self
    {
        return new self($path, $mimeType, $key)->markAsPublic();
    }

    public static function private(string $path, string $mimeType, string $key): self
    {
        return new self($path, $mimeType, $key)->markAsPrivate();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $this->assertNotEmpty($path, 'path');

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = strtolower($this->assertNotEmpty($mimeType, 'MIME type'));

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $this->assertNotEmpty($key, 'key');

        return $this;
    }

    /**
     * @return non-empty-string
     */
    private function assertNotEmpty(?string $value, string $name): string
    {
        if (empty($value = trim($value))) {
            throw new InvalidArgumentException(sprintf('The %s cannot be empty.', $name));
        }

        return $value;
    }
}
