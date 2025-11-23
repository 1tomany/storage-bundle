<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

use function is_dir;
use function is_writable;
use function sys_get_temp_dir;
use function trim;

class DownloadFileRequest implements DownloadFileRequestInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    private readonly string $key;

    /**
     * @var non-empty-string
     */
    private string $directory;

    public function __construct(string $key, ?string $directory = null)
    {
        $this->key = $this->assertNotEmpty($key, 'key');

        $this->setDirectory($directory);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): static
    {
        $directory = trim($directory ?? '');

        if (!is_dir($directory) || !is_writable($directory)) {
            $directory = sys_get_temp_dir();
        }

        $this->directory = $this->assertNotEmpty($directory, 'directory');

        return $this;
    }
}
