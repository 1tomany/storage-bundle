<?php

namespace OneToMany\StorageBundle\Response;

use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

class UploadedFileResponse implements UploadedFileResponseInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $this->assertNotEmpty($url, 'URL');
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
