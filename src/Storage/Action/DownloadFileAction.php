<?php

namespace App\File\Service\Storage\Action;

use App\File\Service\Storage\Record\LocalFileRecord;
use App\File\Service\Storage\Request\DownloadFileRequest;
use App\File\Service\Storage\StorageServiceInterface;

final readonly class DownloadFileAction
{
    public function __construct(private StorageServiceInterface $storage)
    {
    }

    public function act(DownloadFileRequest $request): LocalFileRecord
    {
        return $this->storage->download($request);
    }
}
