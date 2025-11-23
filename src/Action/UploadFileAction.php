<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\UploadFileActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Record\RemoteFileRecord;
use OneToMany\StorageBundle\Request\UploadFileRequest;
use OneToMany\StorageBundle\Service\StorageServiceInterface;

readonly class UploadFileAction implements UploadFileActionInterface
{
    public function __construct(private StorageClientInterface $storageClient)
    {
    }

    public function act(UploadFileRequestInterface $request): UploadedFileResponseInterface
    {
        return $this->storageClient->upload($request);
    }
}
