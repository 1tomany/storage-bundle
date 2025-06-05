<?php

namespace OneToMany\StorageBundle\Record;

use OneToMany\DataUri\SmartFile;
use OneToMany\StorageBundle\Exception\RuntimeException;

use function class_exists;

final readonly class LocalFileRecord implements \Stringable
{
    public function __construct(public string $path)
    {
    }

    public function __toString(): string
    {
        return $this->path;
    }

    public function toSmartFile(): SmartFile // @phpstan-ignore-line
    {
        if (!class_exists(SmartFile::class)) {
            throw new RuntimeException('The file can not be converted to a SmartFile because the library is not installed. Try running "composer require 1tomany/data-uri".');
        }

        return \OneToMany\DataUri\parse_data($this->path, cleanup: true); // @phpstan-ignore-line
    }
}
