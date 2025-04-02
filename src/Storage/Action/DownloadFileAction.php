<?php

namespace OneToMany\StorageBundle\Storage\Action;

use OneToMany\StorageBundle\Storage\Record\LocalFileRecord;
use OneToMany\StorageBundle\Storage\Request\DownloadFileRequest;
use OneToMany\StorageBundle\Storage\StorageServiceInterface;

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
