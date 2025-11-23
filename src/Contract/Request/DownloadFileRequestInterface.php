<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DownloadFileRequestInterface
{
    public const string PREFIX = '__1n__file_';

    public function getRemoteKey(): string;
}
