<?php

namespace OneToMany\StorageBundle\Factory;

use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final readonly class StorageClientFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $storageService): StorageClientInterface
    {
        try {
            $storageClient = $this->container->get($storageService);

            if (!$storageClient instanceof StorageClientInterface) {
                throw new InvalidArgumentException(sprintf('The storage client "%s" does not implement "%s".', $storageService, StorageClientInterface::class));
            }
        } catch (ContainerExceptionInterface $e) {
            throw new InvalidArgumentException(sprintf('The storage service "%s" is not registered with the container.', $storageService), previous: $e);
        }

        return $storageClient;
    }
}
