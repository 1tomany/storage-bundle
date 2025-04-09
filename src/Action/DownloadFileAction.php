<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Record\LocalFileRecord;
use OneToMany\StorageBundle\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Service\StorageServiceInterface;

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
