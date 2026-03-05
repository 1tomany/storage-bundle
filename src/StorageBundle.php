<?php

namespace OneToMany\StorageBundle;

use OneToMany\StorageBundle\Action\DeleteAction;
use OneToMany\StorageBundle\Action\DownloadAction;
use OneToMany\StorageBundle\Action\UploadAction;
use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Contract\Action\DeleteActionInterface;
use OneToMany\StorageBundle\Contract\Action\DownloadActionInterface;
use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Contract\Client\ClientInterface;
use OneToMany\StorageBundle\DependencyInjection\Compiler\AmazonClientPass;
use OneToMany\StorageBundle\Factory\ClientFactory;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

class StorageBundle extends AbstractBundle
{
    protected string $extensionAlias = 'onetomany_storage';

    /**
     * @see Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AmazonClientPass());
    }

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
     *   amazon_client: array{
     *     bucket: ?non-empty-string,
     *     custom_url: ?non-empty-string,
     *     s3_client: non-empty-string,
     *   },
     *   mock_client: array{
     *     bucket: ?non-empty-string,
     *     custom_url: ?non-empty-string,
     *   },
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container
            ->services()
                // Factories
                ->set(ClientFactory::class)
                    ->arg('$container', tagged_locator('onetomany.storage.client', 'key'))

                // Actions
                ->set(DeleteAction::class)
                    ->arg('$client', service(ClientInterface::class))
                    ->alias(DeleteActionInterface::class, service(DeleteAction::class))
                ->set(DownloadAction::class)
                    ->arg('$client', service(ClientInterface::class))
                    ->alias(DownloadActionInterface::class, service(DownloadAction::class))
                ->set(UploadAction::class)
                    ->arg('$client', service(ClientInterface::class))
                    ->alias(UploadActionInterface::class, service(UploadAction::class))

                // Clients
                ->set(ClientInterface::class)
                    ->factory([service(ClientFactory::class), 'create'])
                    ->arg('$service', $config['client'])
                ->set(AmazonClient::class)
                    ->tag('onetomany.storage.client', ['key' => 'amazon'])
                    ->arg('$s3Client', service($config['amazon_client']['s3_client']))
                    ->arg('$bucket', $config['amazon_client']['bucket'] ?? $config['bucket'])
                    ->arg('$customUrl', $config['amazon_client']['custom_url'] ?? $config['custom_url'])
                ->set(MockClient::class)
                    ->tag('onetomany.storage.client', ['key' => 'mock'])
                    ->arg('$bucket', $config['mock_client']['bucket'] ?? $config['bucket'])
                    ->arg('$customUrl', $config['mock_client']['custom_url'] ?? $config['custom_url'])
        ;
    }


}
