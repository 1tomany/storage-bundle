<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Exception\LocalFileNotReadableForUploadException;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Service\StorageServiceInterface;

use function file_exists;

readonly class UploadFileAction
{
    public function __construct(private StorageServiceInterface $storageService)
    {
    }

    public function act(UploadFileRequest $request): RemoteFileRecord
    {
        if (!file_exists($request->file->filePath)) {
            throw new LocalFileNotReadableForUploadException($request->file->filePath);
        }

        return $this->storageService->upload($request);
    }
}
