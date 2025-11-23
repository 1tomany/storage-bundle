<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

use function ltrim;

class DeleteFileRequest implements DeleteFileRequestInterface
{
    use AssertNotEmptyTrait;

    /**
     * @var non-empty-string
     */
    private readonly string $key;

    public function __construct(string $key)
    {
        $this->key = $this->assertNotEmpty(ltrim($key, '/'), 'key');
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
