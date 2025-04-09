<?php

namespace OneToMany\StorageBundle\Service;

use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Request\UploadFileRequest;

interface StorageServiceInterface
{
    public function download(DownloadFileRequest $request): LocalFileRecord;

    public function upload(UploadFileRequest $request): RemoteFileRecord;
}
