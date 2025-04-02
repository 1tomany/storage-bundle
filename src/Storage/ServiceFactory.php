<?php

namespace App\File\Service\Storage;

use App\File\Service\Storage\Exception\InvalidStorageServiceException;
use Psr\Container\ContainerInterface;

final readonly class ServiceFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $service): StorageServiceInterface
    {
        if (!$this->container->has($service)) {
            throw new InvalidStorageServiceException($service);
        }

        $storageService = $this->container->get($service);

        if (!$storageService instanceof StorageServiceInterface) {
            throw new InvalidStorageServiceException($service);
        }

        return $storageService;
    }
}
