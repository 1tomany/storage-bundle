<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

/**
 * @param DefinitionConfigurator<'array'> $configurator
 */
$configurator = static function (DefinitionConfigurator $configurator): void {
    $configurator
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
                        ->stringNode('s3_client')
                            ->cannotBeEmpty()
                            ->defaultValue('aws.s3')
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
};

return $configurator;
