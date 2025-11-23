<?php

namespace OneToMany\StorageBundle\Response;

use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;

class DownloadedFileResponse implements DownloadedFileResponseInterface
{
    /**
     * @var non-empty-string
     */
    private string $path;

    public function __construct(string $path)
    {
        if (empty($path = trim($path))) {
            throw new InvalidArgumentException('The path cannot be empty.');
        }

        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
