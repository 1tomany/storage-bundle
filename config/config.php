<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

/**
 * @param DefinitionConfigurator<'array'> $configurator
 */
$configurator = static function (DefinitionConfigurator $configurator): void {
    $configurator
        ->rootNode()
            ->children()
                ->stringNode('client')
                    ->cannotBeEmpty()
                    ->defaultValue('amazon')
                ->end()
                ->stringNode('bucket')
                    ->cannotBeEmpty()
                ->end()
                ->stringNode('custom_url')
                    ->defaultNull()
                ->end()
            ->end()
        ->end();
};

return $configurator;
