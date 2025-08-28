<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class RiKaZaraiSyliusProductDuplicationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        // CHANGEMENT : nouveau chemin
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        
        if ($config['enabled']) {
            $loader->load('services.xml');
        }

        $container->setParameter('rikazarai_sylius_product_duplication.copy_images', $config['copy_images']);
        $container->setParameter('rikazarai_sylius_product_duplication.copy_associations', $config['copy_associations']);
        $container->setParameter('rikazarai_sylius_product_duplication.duplicate_suffix', $config['duplicate_suffix']);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }
}
