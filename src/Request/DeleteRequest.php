<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\RequestInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function ltrim;

class DeleteRequest implements RequestInterface
{
    /**
     * @var non-empty-string
     */
    private string $key;

    public function __construct(string $key)
    {
        if (!$key = ltrim($key, '/')) {
            throw new InvalidArgumentException('The key cannot be empty.');
        }

        $this->key = $key;
    }

    /**
     * @return non-empty-string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
