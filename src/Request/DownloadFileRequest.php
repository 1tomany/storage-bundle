<?php

namespace OneToMany\StorageBundle\Request;

final readonly class DownloadFileRequest
{
    public const string PREFIX = '__1n__file_';

    public function __construct(public string $remoteKey)
    {
    }
}
