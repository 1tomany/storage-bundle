<?php

namespace App\File\Service\Storage\Action;

use App\File\Service\Storage\Record\RemoteFileRecord;
use App\File\Service\Storage\Request\UploadFileRequest;
use App\File\Service\Storage\StorageServiceInterface;

final readonly class UploadFileAction
{
    public function __construct(private StorageServiceInterface $storage)
    {
    }

    public function act(UploadFileRequest $request): RemoteFileRecord
    {
        return $this->storage->upload($request);
    }
}
