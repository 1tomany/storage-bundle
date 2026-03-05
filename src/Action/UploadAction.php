<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Contract\Client\ClientInterface;
use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\UploadResponse;

readonly class UploadAction implements UploadActionInterface
{
    public function __construct(private ClientInterface $client)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Action\UploadActionInterface
     */
    public function act(UploadRequest $request): UploadResponse
    {
        return $this->client->upload($request);
    }
}
