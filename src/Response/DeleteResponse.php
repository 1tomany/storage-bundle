<?php

namespace OneToMany\StorageBundle\Response;

final readonly class DeleteResponse
{
    public function __construct(private string $key)
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
