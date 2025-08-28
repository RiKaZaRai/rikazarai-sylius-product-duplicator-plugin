<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\EventListener;

use Sylius\Component\Grid\Event\GridDefinitionConverterEvent;

final class AdminProductGridListener
{
    public function addDuplicateActions(GridDefinitionConverterEvent $event): void
    {
        $gridDefinition = $event->getGrid();
        
        if ('sylius_admin_product' !== $gridDefinition->getCode()) {
            return;
        }

        // Ajouter l'action de duplication individuelle
        $gridDefinition->addAction('duplicate', [
            'type' => 'rikazarai_duplicate',
            'label' => 'rikazarai_sylius_product_duplication.ui.duplicate',
            'options' => [
                'link' => [
                    'route' => 'rikazarai_sylius_product_duplication_admin_product_duplicate',
                    'parameters' => ['id' => 'resource.id']
                ]
            ]
        ]);

        // Ajouter l'action de duplication en lot
        $gridDefinition->addBulkAction('bulk_duplicate', [
            'type' => 'rikazarai_bulk_duplicate',
            'label' => 'rikazarai_sylius_product_duplication.ui.bulk_duplicate'
        ]);
    }
}
