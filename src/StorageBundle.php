<?php

namespace OneToMany\StorageBundle;

use OneToMany\StorageBundle\Action\DeleteAction;
use OneToMany\StorageBundle\Action\DownloadAction;
use OneToMany\StorageBundle\Action\UploadAction;
use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Configuration\Configuration;
use OneToMany\StorageBundle\Contract\Action\DeleteActionInterface;
use OneToMany\StorageBundle\Contract\Action\DownloadActionInterface;
use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Contract\Client\ClientInterface;
use OneToMany\StorageBundle\Contract\Configuration\ConfigurationInterface;
use OneToMany\StorageBundle\Factory\ClientFactory;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

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
        $definition
            ->rootNode()
                ->addDefaultsIfNotSet()
                ->children()
                    ->stringNode('client')
                        ->cannotBeEmpty()
                        ->defaultValue('mock')
                    ->end()
                    ->stringNode('bucket')
                        ->cannotBeEmpty()
                        ->defaultValue('@@not-a-real-bucket')
                    ->end()
                    ->stringNode('custom_url')
                        ->defaultNull()
                    ->end()
                    ->arrayNode('amazon_client')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->stringNode('bucket')
                                ->cannotBeEmpty()
                                ->defaultNull()
                            ->end()
                            ->stringNode('custom_url')
                                ->defaultNull()
                            ->end()
                            ->stringNode('version')
                                ->cannotBeEmpty()
                                ->defaultValue('latest')
                            ->end()
                            ->stringNode('region')
                                ->cannotBeEmpty()
                                ->defaultValue('auto')
                            ->end()
                            ->stringNode('endpoint')
                                ->defaultNull()
                            ->end()
                            ->stringNode('key')
                                ->cannotBeEmpty()
                                ->defaultValue('')
                            ->end()
                            ->stringNode('secret')
                                ->cannotBeEmpty()
                                ->defaultValue('')
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('mock_client')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->stringNode('bucket')
                                ->cannotBeEmpty()
                                ->defaultNull()
                            ->end()
                            ->stringNode('custom_url')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @see Symfony\Component\DependencyInjection\Extension\ConfigurableExtensionInterface
     *
     * @param array{
     *   client: non-empty-string,
     *   bucket: non-empty-string,
     *   custom_url: ?non-empty-string,
     *   amazon_client: array{
     *     bucket: ?non-empty-string,
     *     custom_url: ?non-empty-string,
     *     version: non-empty-string,
     *     region: non-empty-string,
     *     endpoint: ?non-empty-string,
     *     key: string,
     *     secret: string,
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

                // Configuration
                ->set(ConfigurationInterface::class, Configuration::class)
                    ->arg('$version', $config['amazon_client']['version'])
                    ->arg('$region', $config['amazon_client']['region'])
                    ->arg('$endpoint', $config['amazon_client']['endpoint'])
                    ->arg('$key', $config['amazon_client']['key'])
                    ->arg('$secret', $config['amazon_client']['secret'])

                // Clients
                ->set(ClientInterface::class)
                    ->factory([service(ClientFactory::class), 'create'])
                    ->arg('$service', $config['client'])
                ->set(AmazonClient::class)
                    ->tag('onetomany.storage.client', ['key' => 'amazon'])
                    ->arg('$configuration', service(ConfigurationInterface::class))
                    ->arg('$httpClient', service(HttpClientInterface::class))
                    ->arg('$bucket', $config['amazon_client']['bucket'] ?? $config['bucket'])
                    ->arg('$customUrl', $config['amazon_client']['custom_url'] ?? $config['custom_url'])
                ->set(MockClient::class)
                    ->tag('onetomany.storage.client', ['key' => 'mock'])
                    ->arg('$bucket', $config['mock_client']['bucket'] ?? $config['bucket'])
                    ->arg('$customUrl', $config['mock_client']['custom_url'] ?? $config['custom_url'])
        ;
    }
}
