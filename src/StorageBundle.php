<?php

namespace OneToMany\StorageBundle;

use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Factory\ClientFactory;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class StorageBundle extends AbstractBundle
{
    protected string $extensionAlias = 'onetomany_storage';

    /**
     * @see Symfony\Component\Config\Definition\ConfigurableInterface
     *
     * @param DefinitionConfigurator<'array'> $definition
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/config.php');
    }

    /**
     * @param array{
     *   client: non-empty-string,
     *   bucket: non-empty-string,
     *   custom_url: ?non-empty-string,
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        if ($builder->hasDefinition(ClientFactory::class)) {
            $builder
                ->getDefinition(ClientFactory::class)
                ->setArgument('$service', $config['client']);
        }

        if ($builder->hasDefinition(AmazonClient::class)) {
            $builder
                ->getDefinition(AmazonClient::class)
                ->setArgument('$bucket', $config['bucket'])
                ->setArgument('$customUrl', $config['custom_url']);
        }

        if ($builder->hasDefinition(MockClient::class)) {
            $builder
                ->getDefinition(MockClient::class)
                ->setArgument('$bucket', $config['bucket'])
                ->setArgument('$customUrl', $config['custom_url']);
        }
    }
}
