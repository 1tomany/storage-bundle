<?php

namespace OneToMany\StorageBundle\Record;

use OneToMany\DataUri\SmartFile;

final readonly class LocalFileRecord implements \Stringable
{
    public function __construct(public string $filePath)
    {
    }

    public function __toString(): string
    {
        return $this->filePath;
    }

    // @phpstan-ignore-next-line
    public function asSmartFile(): SmartFile
    {
        if (!\class_exists(SmartFile::class)) {
            throw new \LogicException('The file can not be converted to a SmartFile because the Data URI library is not installed. Try running "composer require 1tomany/data-uri".');
        }

        // @phpstan-ignore-next-line
        return \OneToMany\DataUri\parse_data(data: $this->filePath, deleteOriginalFile: true);
    }
}
