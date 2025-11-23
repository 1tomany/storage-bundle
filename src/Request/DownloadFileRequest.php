<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function sys_get_temp_dir;
use function trim;

class DownloadFileRequest implements DownloadFileRequestInterface
{
    private ?string $directory = null;

    /**
     * @var non-empty-string
     */
    private string $key;

    public function __construct(string $key)
    {
        if (empty($key = trim($key))) {
            throw new InvalidArgumentException('The key cannot be an empty string.');
        }

        $this->key = $key;
    }

    public function getDirectory(): string
    {
        return ($this->directory ?: sys_get_temp_dir()) ?: '/tmp';
    }

    public function setDirectory(?string $directory): static
    {
        $this->directory = trim($directory ?? '') ?: null;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
