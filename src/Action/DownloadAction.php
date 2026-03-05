<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\DownloadActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Response\DownloadResponse;

readonly class DownloadAction implements DownloadActionInterface
{
    public function __construct(private StorageClientInterface $client)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Action\DownloadActionInterface
     */
    public function act(DownloadRequest $request): DownloadResponse
    {
        return $this->client->download($request);
    }
}
