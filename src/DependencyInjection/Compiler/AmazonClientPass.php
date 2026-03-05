<?php

namespace OneToMany\StorageBundle\DependencyInjection\Compiler;

use Aws\S3\S3ClientInterface;
use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use function sprintf;

class AmazonClientPass implements CompilerPassInterface
{
    /**
     * @see Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(AmazonClient::class)) {
            return;
        }

        /** @var non-empty-list<array<non-empty-string, mixed>> $storageConfig */
        $storageConfig = $container->getExtensionConfig('onetomany_storage');

        print_r($storageConfig);

        $s3ClientArg = $container
            ->getDefinition(AmazonClient::class)
            ->getArgument('$s3Client');

        if (!$s3ClientArg instanceof Reference) {
            throw new InvalidArgumentException(sprintf('The "$s3Client" argument required by "%s" is expected to be a reference to a service.', AmazonClient::class));
        }

        $s3ClientId = $s3ClientArg->__toString();

        if (!$container->has($s3ClientId)) {
            throw new InvalidArgumentException(sprintf('The service "%s" required by "%s" does not exist.', $s3ClientId, AmazonClient::class));
        }

        if (!$container->get($s3ClientId) instanceof S3ClientInterface) {
            throw new InvalidArgumentException(sprintf('The service "%s" required by "%s" is not an instance of "%s". Try running "composer require aws/aws-sdk-php-symfony" to install the AWS SDK.', $s3ClientId, AmazonClient::class, S3ClientInterface::class));
        }
    }
}
