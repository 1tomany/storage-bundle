<?php

namespace OneToMany\StorageBundle\Response;

use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Trait\AssertNotEmptyTrait;

class DeletedFileResponse implements DeletedFileResponseInterface
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
