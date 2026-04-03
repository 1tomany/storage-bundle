<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function is_dir;
use function is_writable;
use function sprintf;
use function sys_get_temp_dir;
use function trim;

class DownloadRequest
{
    /**
     * @var non-empty-string
     */
    private string $key;

    /**
     * @var non-empty-string
     */
    private string $directory;

    public const string PREFIX = '__onetomany__storage_';

    public function __construct(
        string $key,
        ?string $directory = null,
    ) {
        $this->withKey($key);
        $this->toDirectory($directory);
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
            throw new InvalidArgumentException('The key cannot be empty.');
        }

        $this->key = $key;

        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @throws InvalidArgumentException when the directory name is empty
     * @throws InvalidArgumentException when the directory is not writable
     */
    public function toDirectory(?string $directory): static
    {
        $directory = trim((string) $directory);

        if ('' === $directory) {
            throw new InvalidArgumentException('The directory name cannot be empty.');
        }

        if (!is_dir($directory) || !is_writable($directory)) {
            $directory = sys_get_temp_dir();
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf('The directory "%s" is not writable.', $directory));
        }

        $this->directory = $directory;

        return $this;
    }
}
