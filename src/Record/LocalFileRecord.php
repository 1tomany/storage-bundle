<?php

namespace OneToMany\StorageBundle\Record;

use OneToMany\DataUri\SmartFile;
use OneToMany\StorageBundle\Exception\RuntimeException;

use function class_exists;

final readonly class LocalFileRecord implements \Stringable
{
    public function __construct(public string $filePath)
    {
    }

    public function __toString(): string
    {
        return $this->filePath;
    }

    public function toSmartFile(): SmartFile // @phpstan-ignore-line
    {
        if (!class_exists(SmartFile::class)) {
            throw new RuntimeException('The file can not be converted to a SmartFile because the library is not installed. Try running "composer require 1tomany/data-uri".');
        }

        return \OneToMany\DataUri\parse_data(data: $this->filePath, deleteOriginalFile: true); // @phpstan-ignore-line
    }
}
