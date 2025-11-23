<?php

namespace OneToMany\StorageBundle\Contract\Client;

use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;

interface StorageClientInterface
{
    /**
     * @return non-empty-string
     */
    public function getBucket(): string;

    /**
     * @return ?non-empty-string
     */
    public function getCustomUrl(): ?string;

    public function upload(UploadFileRequestInterface $request): UploadedFileResponseInterface;

    public function download(DownloadFileRequestInterface $request): DownloadedFileResponseInterface;

    public function delete(DeleteFileRequestInterface $request): DeletedFileResponseInterface;
}
