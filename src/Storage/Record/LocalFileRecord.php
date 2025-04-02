<?php

namespace OneToMany\StorageBundle\Storage\Record;

final readonly class LocalFileRecord implements \Stringable
{
    public function __construct(public string $path)
    {
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
