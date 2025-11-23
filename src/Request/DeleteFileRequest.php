<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;

class DeleteFileRequest implements DeleteFileRequestInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $this->assertNotEmpty($key, 'key');
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
