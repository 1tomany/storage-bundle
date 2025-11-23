<?php

namespace OneToMany\StorageBundle\Tests\Factory;

use OneToMany\StorageBundle\Client\Mock\MockStorageClient;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Factory\StorageClientFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

#[Group('UnitTests')]
#[Group('FactoryTests')]
final class StorageClientFactoryTest extends TestCase
{
    public function testCreatingServiceRequiresServiceToExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The storage service "invalid" is not registered with the container.');

        new StorageClientFactory($this->createContainer())->create('invalid');
    }

    public function testCreatingServiceRequiresServiceToImplementStorageClientInterface(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The storage client "error" does not implement "OneToMany\StorageBundle\Contract\Client\StorageClientInterface".');

        new StorageClientFactory($this->createContainer())->create('error');
    }

    private function createContainer(): ContainerInterface
    {
        $container = new class implements ContainerInterface {
            /**
             * @var array{
             *   mock: MockStorageClient,
             *   error: \InvalidArgumentException,
             * }
             */
            private array $services;

            public function __construct()
            {
                $this->services = [
                    'mock' => new MockStorageClient('bucket'),
                    'error' => new \InvalidArgumentException(),
                ];
            }

            public function get(string $id): mixed
            {
                if ('invalid' === $id) {
                    throw new class('no good') extends \InvalidArgumentException implements NotFoundExceptionInterface {};
                }

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
