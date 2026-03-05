<?php

namespace OneToMany\StorageBundle\Contract\Client;

use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;
use OneToMany\StorageBundle\Response\DownloadResponse;
use OneToMany\StorageBundle\Response\UploadResponse;

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

    public function upload(UploadRequest $request): UploadResponse;

    public function download(DownloadRequest $request): DownloadResponse;

    public function delete(DeleteRequest $request): DeleteResponse;
}
