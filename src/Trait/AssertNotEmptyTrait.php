<?php

namespace OneToMany\StorageBundle\Trait;

use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function sprintf;
use function trim;

trait AssertNotEmptyTrait
{
    /**
     * @return non-empty-string
     */
    protected function assertNotEmpty(?string $value, string $name): string
    {
        if (empty($value = trim($value ?? ''))) {
            throw new InvalidArgumentException(sprintf('The %s cannot be empty.', $name));
        }

        return $value;
    }
}
