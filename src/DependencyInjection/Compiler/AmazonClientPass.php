<?php

namespace OneToMany\StorageBundle\DependencyInjection\Compiler;

use Aws\S3\S3ClientInterface;
use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Exception\InvalidArgumentException;
use OneToMany\StorageBundle\Exception\RuntimeException;
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

        if (!interface_exists(S3ClientInterface::class, true)) {
            throw new RuntimeException(sprintf('The AWS SDK is required to use "%s" but was not found. Try running "composer require aws/aws-sdk-php-symfony" to install it.', AmazonClient::class));
        }

        $s3ClientArg = $container
            ->getDefinition(AmazonClient::class)
            ->getArgument('$s3Client');

        if (!$s3ClientArg instanceof Reference) {
            throw new InvalidArgumentException(sprintf('The "$s3Client" argument required by "%s::__construct()" is expected to be a reference to a service.', AmazonClient::class));
        }

        $s3ClientId = $s3ClientArg->__toString();

        if (!$container->hasDefinition($s3ClientId)) {
            throw new InvalidArgumentException(sprintf('The service "%s" required by "%s" does not exist.', $s3ClientId, AmazonClient::class));
        }

        $s3ClientArgClass = $container->getDefinition($s3ClientId)->getClass();

        if (null === $s3ClientArgClass) {
            throw new InvalidArgumentException(sprintf('The "$s3Client" argument required by "%s::__construct()" must reference a class.', AmazonClient::class));
        }

        if (!\is_a($s3ClientArgClass, S3ClientInterface::class, true)) {
            throw new InvalidArgumentException(sprintf('The "$s3Client" argument required by "%s::__construct()" must be an instance of "%s", "%s" provided.', AmazonClient::class, S3ClientInterface::class, $s3ClientArgClass));
        }
    }
}
