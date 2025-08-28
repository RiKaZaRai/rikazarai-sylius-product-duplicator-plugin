<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rikazarai_sylius_product_duplication');
        
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                    ->info('Enable or disable the product duplication plugin')
                ->end()
                ->booleanNode('copy_images')
                    ->defaultTrue()
                    ->info('Copy product images when duplicating')
                ->end()
                ->booleanNode('copy_associations')
                    ->defaultTrue()
                    ->info('Copy product associations when duplicating')
                ->end()
                ->scalarNode('duplicate_suffix')
                    ->defaultValue(' (Copie)')
                    ->info('Suffix to add to duplicated product names')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
