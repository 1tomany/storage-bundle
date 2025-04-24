<?php

namespace OneToMany\StorageBundle\Record;

use OneToMany\DataUri\SmartFile;

use function OneToMany\DataUri\parse_data;

final readonly class LocalFileRecord implements \Stringable
{
    public SmartFile $file;

    public function __construct(string $filePath)
    {
        $this->file = parse_data(data: $filePath, deleteOriginalFile: true);
    }

    public function __toString(): string
    {
        return $this->file->filePath;
    }
}
