<?php

namespace OneToMany\StorageBundle\Record;

final readonly class LocalFileRecord implements \Stringable
{
    public function __construct(public string $filePath)
    {
    }

    public function __toString(): string
    {
        return $this->filePath;
    }
}
