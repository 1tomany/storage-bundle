<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\DownloadFileActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;

readonly class DownloadFileAction implements DownloadFileActionInterface
{
    public function __construct(private StorageClientInterface $storageClient)
    {
    }

    public function act(DownloadFileRequestInterface $request): DownloadedFileResponseInterface
    {
        return $this->storageClient->download($request);
    }
}
