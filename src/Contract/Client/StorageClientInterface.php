<?php

namespace OneToMany\StorageBundle\Contract\Client;

use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;

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

    public function upload(UploadRequest $request): UploadedFileResponseInterface;

    public function download(DownloadRequest $request): DownloadedFileResponseInterface;

    public function delete(DeleteRequest $request): DeletedFileResponseInterface;
}
