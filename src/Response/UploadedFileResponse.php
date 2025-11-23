<?php

namespace OneToMany\StorageBundle\Response;

use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;

class UploadedFileResponse implements UploadedFileResponseInterface
{
    /**
     * @var non-empty-string
     */
    private string $url;

    public function __construct(string $url)
    {
        if (empty($url = \trim($url))) {
            throw new InvalidArgumentException('The URL cannot be empty.');
        }

        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
