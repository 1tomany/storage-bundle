<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Service\StorageServiceInterface;

readonly class DownloadFileAction
{
    public function __construct(private StorageServiceInterface $storageService)
    {
    }

    public function act(DownloadFileRequest $request): LocalFileRecord
    {
        return $this->storageService->download($request);
    }
}
