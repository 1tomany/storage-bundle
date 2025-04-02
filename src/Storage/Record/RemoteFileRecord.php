<?php

namespace OneToMany\StorageBundle\Storage\Record;

final readonly class RemoteFileRecord implements \Stringable
{
    public function __construct(public string $url)
    {
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
