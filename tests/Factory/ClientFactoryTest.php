<?php

namespace OneToMany\StorageBundle\Tests\Factory;

use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Factory\ClientFactory;
use OneToMany\StorageBundle\Factory\Exception\CreatingClientFailedServiceNotFoundException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

#[Group('UnitTests')]
#[Group('FactoryTests')]
final class ClientFactoryTest extends TestCase
{
    public function testCreatingServiceRequiresServiceToExist(): void
    {
        $this->expectException(CreatingClientFailedServiceNotFoundException::class);

        new ClientFactory($this->createContainer())->create('invalid');
    }

    public function testCreatingServiceRequiresServiceToImplementStorageClientInterface(): void
    {
        $this->expectException(CreatingClientFailedServiceNotFoundException::class);

        new ClientFactory($this->createContainer())->create('error');
    }

    private function createContainer(): ContainerInterface
    {
        $container = new class implements ContainerInterface {
            /**
             * @var array{
             *   mock: MockClient,
             *   error: \InvalidArgumentException,
             * }
             */
            private array $services;

            public function __construct()
            {
                $this->services = [
                    'mock' => new MockClient('bucket'),
                    'error' => new \InvalidArgumentException(),
                ];
            }

            public function get(string $id): mixed
            {
                return $this->services[$id] ?? null;
            }

            public function has(string $id): bool
            {
                return isset($this->services[$id]);
            }
        };

        return $container;
    }
}
