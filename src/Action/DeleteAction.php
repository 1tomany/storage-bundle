<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\DeleteActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Request\DeleteRequest;
use OneToMany\StorageBundle\Response\DeleteResponse;

readonly class DeleteAction implements DeleteActionInterface
{
    public function __construct(private StorageClientInterface $client)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Action\DeleteActionInterface
     */
    public function act(DeleteRequest $request): DeleteResponse
    {
        return $this->client->delete($request);
    }
}
