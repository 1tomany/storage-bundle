<?php

namespace OneToMany\StorageBundle\Action;

use OneToMany\StorageBundle\Contract\Action\DeleteActionInterface;
use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;
use OneToMany\StorageBundle\Request\DeleteRequest;

readonly class DeleteAction implements DeleteActionInterface
{
    public function __construct(private StorageClientInterface $client)
    {
    }

    /**
     * @see OneToMany\StorageBundle\Contract\Action\DeleteActionInterface
     */
    public function act(DeleteRequest $request): DeletedFileResponseInterface
    {
        return $this->client->delete($request);
    }
}
