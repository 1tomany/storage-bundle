<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

use function is_dir;
use function is_writable;
use function sys_get_temp_dir;
use function trim;

class DownloadRequest implements DownloadFileRequestInterface
{
    use AssertNotEmptyTrait;

    /** @var non-empty-string */
    private string $key = self::DEFAULT_KEY;

    /** @var non-empty-string */
    private string $directory;

    public const string DEFAULT_KEY = '__unknown_key__';

    public function __construct(
        string $key,
        ?string $directory = null,
    ) {
        $this->usingKey($key);
        $this->toDirectory($directory);
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
        $this->key = trim($key ?? '') ?: self::DEFAULT_KEY;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function toDirectory(?string $directory): static
    {
        $directory = trim($directory ?? '');

        if (!is_dir($directory) || !is_writable($directory)) {
            $directory = sys_get_temp_dir();
        }

        if ('' === $directory) {
            throw new InvalidArgumentException('The directory cannot be empty.');
        }

        $this->directory = $directory;

        return $this;
    }
}
