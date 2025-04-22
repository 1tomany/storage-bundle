<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Service\StorageServiceInterface;

readonly class UploadFileAction
{
    public function __construct(private StorageServiceInterface $storageService)
    {
    }

    public function act(UploadFileRequest $request): RemoteFileRecord
    {
        return $this->storageService->upload($request);
    }
}
