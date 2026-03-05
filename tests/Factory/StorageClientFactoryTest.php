<?php

namespace OneToMany\StorageBundle\Tests\Factory;

use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Factory\Exception\CreatingClientFailedServiceNotFoundException;
use OneToMany\StorageBundle\Factory\StorageClientFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function sprintf;

#[Group('UnitTests')]
#[Group('FactoryTests')]
final class StorageClientFactoryTest extends TestCase
{
    public function testCreatingServiceRequiresServiceToExist(): void
    {
        $this->expectException(CreatingClientFailedServiceNotFoundException::class);

        new StorageClientFactory($this->createContainer())->create('invalid');
    }

    public function testCreatingServiceRequiresServiceToImplementStorageClientInterface(): void
    {
        $this->expectException(CreatingClientFailedServiceNotFoundException::class);

        new StorageClientFactory($this->createContainer())->create('error');
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
                    'mock' => new MockClient('mock-bucket'),
                    'error' => new \InvalidArgumentException('Error!'),
                ];
            }

            public function get(string $id): mixed
            {
                // if (!$this->has($id)) {
                //     throw new class(sprintf('The service "%s" is not registered with this container.', $id)) extends \InvalidArgumentException implements NotFoundExceptionInterface {};
                // }

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
