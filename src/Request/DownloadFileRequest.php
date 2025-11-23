<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;

use function sys_get_temp_dir;
use function trim;

class DownloadFileRequest implements DownloadFileRequestInterface
{
    use AssertNotEmptyTrait;

    private ?string $directory = null;

    /**
     * @var non-empty-string
     */
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $this->assertNotEmpty($key, 'key');
    }

    public function getDirectory(): string
    {
        /** @var non-empty-string $directory */
        $directory = $this->directory ?: sys_get_temp_dir();

        return $directory;
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
