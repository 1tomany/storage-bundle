<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Request\UploadRequest;

readonly class UploadFileAction implements UploadActionInterface
{
    public function __construct(private StorageClientInterface $client)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Action\UploadActionInterface
     */
    public function act(UploadRequest $request): UploadedFileResponseInterface
    {
        return $this->client->upload($request);
    }
}
