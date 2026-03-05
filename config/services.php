<?php

use OneToMany\StorageBundle\Action\DeleteAction;
use OneToMany\StorageBundle\Action\DownloadAction;
use OneToMany\StorageBundle\Action\UploadAction;
use OneToMany\StorageBundle\Client\Amazon\AmazonClient;
use OneToMany\StorageBundle\Client\Mock\MockClient;
use OneToMany\StorageBundle\Contract\Action\DeleteActionInterface;
use OneToMany\StorageBundle\Contract\Action\DownloadActionInterface;
use OneToMany\StorageBundle\Contract\Action\UploadActionInterface;
use OneToMany\StorageBundle\Contract\Client\ClientInterface;
use OneToMany\StorageBundle\Factory\ClientFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
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
                // ->arg('$id', 'poppler')
            ->set(AmazonClient::class)
                ->tag('onetomany.storage.client', ['key' => 'amazon'])
            ->set(MockClient::class)
                ->tag('onetomany.storage.client', ['key' => 'mock'])
    ;
};
