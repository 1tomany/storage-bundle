<?php

namespace OneToMany\StorageBundle\Storage;

use OneToMany\StorageBundle\Storage\Record\LocalFileRecord;
use OneToMany\StorageBundle\Storage\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Storage\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Storage\Request\UploadFileRequest;

interface StorageServiceInterface
{
    public function download(DownloadFileRequest $request): LocalFileRecord;

    public function upload(UploadFileRequest $request): RemoteFileRecord;
}
