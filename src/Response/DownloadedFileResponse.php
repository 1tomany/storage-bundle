<?php

namespace OneToMany\StorageBundle\Response;

use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

readonly class DownloadedFileResponse implements DownloadedFileResponseInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $this->assertNotEmpty($path, 'path');
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
