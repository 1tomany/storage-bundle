<?php

namespace OneToMany\StorageBundle\Request;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;

class DownloadFileRequest implements DownloadFileRequestInterface
{
    public function __construct(private string $remoteKey)
    {
    }

    public function getRemoteKey(): string
    {
        return $this->remoteKey;
    }
}
