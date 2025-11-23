<?php

namespace OneToMany\StorageBundle\Contract\Client;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;

interface StorageClientInterface
{
    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface;

    // public function upload(UploadFileRequest $request): RemoteFileRecord;
}
