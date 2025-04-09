<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\StorageServiceInterface;

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
