<?php

namespace OneToMany\StorageBundle\Factory;

use OneToMany\StorageBundle\Contract\Client\StorageClientInterface;
use OneToMany\StorageBundle\Factory\Exception\CreatingClientFailedServiceNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final readonly class StorageClientFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $service): StorageClientInterface
    {
        try {
            $client = $this->container->get($service);

            if (!$client instanceof StorageClientInterface) {
                throw new CreatingClientFailedServiceNotFoundException($service);
            }
        } catch (ContainerExceptionInterface $e) {
            throw new CreatingClientFailedServiceNotFoundException($service, $e);
        }

        return $client;
    }
}
