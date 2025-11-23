<?php

namespace OneToMany\StorageBundle\Contract\Client;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;

interface StorageClientInterface
{
    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface;

    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface;
}
