<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\Service;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTranslationInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Core\Factory\ChannelPricingFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ObjectManager;

final class ProductDuplicatorService
{
    public function __construct(
        private ProductFactoryInterface $productFactory,
        private ProductVariantFactoryInterface $productVariantFactory,
        private ChannelPricingFactoryInterface $channelPricingFactory,
        private ObjectManager $productManager,
        private RepositoryInterface $productRepository,
        private RepositoryInterface $productTranslationRepository
    ) {}

    public function duplicateProduct(ProductInterface $originalProduct): ProductInterface
    {
        /** @var ProductInterface $duplicatedProduct */
        $duplicatedProduct = $this->productFactory->createNew();
        
        // Copier les propriétés de base
        $this->copyBasicProperties($originalProduct, $duplicatedProduct);
        
        // Copier les traductions
        $this->copyTranslations($originalProduct, $duplicatedProduct);
        
        // Copier les variantes
        $this->copyVariants($originalProduct, $duplicatedProduct);
        
        // Copier les associations et relations
        $this->copyAssociations($originalProduct, $duplicatedProduct);
        
        return $duplicatedProduct;
    }

    private function copyBasicProperties(ProductInterface $original, ProductInterface $duplicate): void
    {
        $duplicate->setCode($this->generateUniqueCode($original->getCode()));
        $duplicate->setEnabled($original->isEnabled());
        
        // Copier les catégories
        if ($original->getTaxCategory()) {
            $duplicate->setTaxCategory($original->getTaxCategory());
        }
        
        if ($original->getShippingCategory()) {
            $duplicate->setShippingCategory($original->getShippingCategory());
        }

        // Copier les canaux
        foreach ($original->getChannels() as $channel) {
            $duplicate->addChannel($channel);
        }

        // Copier les taxons
        foreach ($original->getTaxons() as $taxon) {
            $duplicate->addTaxon($taxon);
        }

        // Copier les attributs
        foreach ($original->getAttributes() as $attribute) {
            $duplicate->addAttribute($attribute);
        }

        // Copier les options
        foreach ($original->getOptions() as $option) {
            $duplicate->addOption($option);
        }
    }

    private function copyTranslations(ProductInterface $original, ProductInterface $duplicate): void
    {
        foreach ($original->getTranslations() as $translation) {
            /** @var ProductTranslationInterface $translation */
            $newTranslation = $duplicate->getTranslation($translation->getLocale());
            $newTranslation->setName($translation->getName() . ' (Copie)');
            $newTranslation->setSlug($this->generateUniqueSlug($translation->getSlug(), $translation->getLocale()));
            $newTranslation->setDescription($translation->getDescription());
            $newTranslation->setShortDescription($translation->getShortDescription());
            $newTranslation->setMetaKeywords($translation->getMetaKeywords());
            $newTranslation->setMetaDescription($translation->getMetaDescription());
        }
    }

    private function copyVariants(ProductInterface $original, ProductInterface $duplicate): void
    {
        foreach ($original->getVariants() as $originalVariant) {
            /** @var ProductVariantInterface $originalVariant */
            $duplicatedVariant = $this->duplicateVariant($originalVariant, $duplicate);
            $duplicate->addVariant($duplicatedVariant);
        }
    }

    private function duplicateVariant(ProductVariantInterface $originalVariant, ProductInterface $newProduct): ProductVariantInterface
    {
        /** @var ProductVariantInterface $duplicatedVariant */
        $duplicatedVariant = $this->productVariantFactory->createNew();
        
        $duplicatedVariant->setCode($this->generateUniqueCode($originalVariant->getCode()));
        $duplicatedVariant->setProduct($newProduct);
        $duplicatedVariant->setPosition($originalVariant->getPosition());
        $duplicatedVariant->setShippingRequired($originalVariant->isShippingRequired());
        
        // Copier les dimensions
        $duplicatedVariant->setWeight($originalVariant->getWeight());
        $duplicatedVariant->setWidth($originalVariant->getWidth());
        $duplicatedVariant->setHeight($originalVariant->getHeight());
        $duplicatedVariant->setDepth($originalVariant->getDepth());

        // Copier les options de variante
        foreach ($originalVariant->getOptionValues() as $optionValue) {
            $duplicatedVariant->addOptionValue($optionValue);
        }

        // Copier les prix de canal
        foreach ($originalVariant->getChannelPricings() as $channelPricing) {
            $newChannelPricing = $this->channelPricingFactory->createNew();
            $newChannelPricing->setChannelCode($channelPricing->getChannelCode());
            $newChannelPricing->setPrice($channelPricing->getPrice());
            $newChannelPricing->setOriginalPrice($channelPricing->getOriginalPrice());
            $newChannelPricing->setMinimumPrice($channelPricing->getMinimumPrice());
            $newChannelPricing->setProductVariant($duplicatedVariant);
            
            $duplicatedVariant->addChannelPricing($newChannelPricing);
        }

        // Copier les traductions de variante
        foreach ($originalVariant->getTranslations() as $translation) {
            $newTranslation = $duplicatedVariant->getTranslation($translation->getLocale());
            $newTranslation->setName($translation->getName() . ' (Copie)');
        }

        return $duplicatedVariant;
    }

    private function copyAssociations(ProductInterface $original, ProductInterface $duplicate): void
    {
        // Copier les associations de produits
        foreach ($original->getAssociations() as $association) {
            $duplicate->addAssociation($association);
        }

        // Copier les images (si nécessaire)
        foreach ($original->getImages() as $image) {
            $duplicate->addImage($image);
        }
    }

    private function generateUniqueCode(string $baseCode): string
    {
        $counter = 1;
        $newCode = $baseCode . '-copy-' . $counter;
        
        while ($this->productRepository->findOneBy(['code' => $newCode])) {
            $counter++;
            $newCode = $baseCode . '-copy-' . $counter;
        }
        
        return $newCode;
    }

    private function generateUniqueSlug(string $baseSlug, string $locale): string
    {
        $counter = 1;
        $newSlug = $baseSlug . '-copy-' . $counter;
        
        while ($this->productTranslationRepository->findOneBy(['slug' => $newSlug, 'locale' => $locale])) {
            $counter++;
            $newSlug = $baseSlug . '-copy-' . $counter;
        }
        
        return $newSlug;
    }
}
