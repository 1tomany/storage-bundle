<?php

namespace OneToMany\StorageBundle\Request;

final readonly class DownloadFileRequest
{
    public function __construct(public string $remoteKey)
    {
    }
}
