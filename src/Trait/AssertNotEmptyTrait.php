<?php

namespace OneToMany\StorageBundle\Trait;

use OneToMany\StorageBundle\Exception\InvalidArgumentException;

use function is_string;
use function sprintf;
use function trim;

trait AssertNotEmptyTrait
{
    /**
     * @return non-empty-string
     */
    protected function assertNotEmpty(mixed $value, string $name): string
    {
        $value = is_string($value) ? trim($value) : null;

        if (empty($value)) {
            throw new InvalidArgumentException(sprintf('The %s cannot be empty.', $name));
        }

        return $value;
    }
}
