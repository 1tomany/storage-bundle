<?php

namespace OneToMany\StorageBundle\Request;

use function ltrim;

class DeleteRequest
{
    public function __construct(private string $key)
    {
        $this->key = ltrim($key, '/');
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
