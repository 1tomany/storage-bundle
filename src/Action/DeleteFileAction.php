<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\DeleteFileActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;

readonly class DeleteFileAction implements DeleteFileActionInterface
{
    public function __construct(private StorageClientInterface $storageClient)
    {
    }

    public function act(DeleteFileRequestInterface $request): DeletedFileResponseInterface
    {
        return $this->storageClient->delete($request);
    }
}
