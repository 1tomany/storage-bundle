<?php

namespace OneToMany\StorageBundle\Storage\Action;

use OneToMany\StorageBundle\Storage\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Storage\Request\UploadFileRequest;
use OneToMany\StorageBundle\Storage\StorageServiceInterface;

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
