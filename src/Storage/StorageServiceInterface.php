<?php

namespace App\File\Service\Storage;

use App\File\Service\Storage\Record\LocalFileRecord;
use App\File\Service\Storage\Record\RemoteFileRecord;
use App\File\Service\Storage\Request\DownloadFileRequest;
use App\File\Service\Storage\Request\UploadFileRequest;

interface StorageServiceInterface
{
    public function download(DownloadFileRequest $request): LocalFileRecord;

    public function upload(UploadFileRequest $request): RemoteFileRecord;
}
