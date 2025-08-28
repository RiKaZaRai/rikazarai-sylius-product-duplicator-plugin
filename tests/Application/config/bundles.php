<?php

// tests/Application/config/bundles.php
<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Sylius\Bundle\OrderBundle\SyliusOrderBundle::class => ['all' => true],
    Sylius\Bundle\MoneyBundle\SyliusMoneyBundle::class => ['all' => true],
    Sylius\Bundle\CurrencyBundle\SyliusCurrencyBundle::class => ['all' => true],
    Sylius\Bundle\LocaleBundle\SyliusLocaleBundle::class => ['all' => true],
    Sylius\Bundle\ProductBundle\SyliusProductBundle::class => ['all' => true],
    Sylius\Bundle\ChannelBundle\SyliusChannelBundle::class => ['all' => true],
    Sylius\Bundle\AttributeBundle\SyliusAttributeBundle::class => ['all' => true],
    Sylius\Bundle\TaxationBundle\SyliusTaxationBundle::class => ['all' => true],
    Sylius\Bundle\ShippingBundle\SyliusShippingBundle::class => ['all' => true],
    Sylius\Bundle\PaymentBundle\SyliusPaymentBundle::class => ['all' => true],
    Sylius\Bundle\MailerBundle\SyliusMailerBundle::class => ['all' => true],
    Sylius\Bundle\InventoryBundle\SyliusInventoryBundle::class => ['all' => true],
    Sylius\Bundle\TaxonomyBundle\SyliusTaxonomyBundle::class => ['all' => true],
    Sylius\Bundle\UserBundle\SyliusUserBundle::class => ['all' => true],
    Sylius\Bundle\CustomerBundle\SyliusCustomerBundle::class => ['all' => true],
    Sylius\Bundle\UiBundle\SyliusUiBundle::class => ['all' => true],
    Sylius\Bundle\ReviewBundle\SyliusReviewBundle::class => ['all' => true],
    Sylius\Bundle\CoreBundle\SyliusCoreBundle::class => ['all' => true],
    Sylius\Bundle\ResourceBundle\SyliusResourceBundle::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
    winzou\Bundle\StateMachineBundle\winzouStateMachineBundle::class => ['all' => true],
    Sonata\BlockBundle\SonataBlockBundle::class => ['all' => true],
    Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    FOS\RestBundle\FOSRestBundle::class => ['all' => true],
    Knp\Bundle\GaufretteBundle\KnpGaufretteBundle::class => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    Liip\ImagineBundle\LiipImagineBundle::class => ['all' => true],
    Payum\Bundle\PayumBundle\PayumBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true],
    WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle::class => ['all' => true],
    Sylius\Bundle\FixturesBundle\SyliusFixturesBundle::class => ['all' => true, 'test' => true],
    Sylius\Bundle\PayumBundle\SyliusPayumBundle::class => ['all' => true],
    Sylius\Bundle\ThemeBundle\SyliusThemeBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle::class => ['dev' => true, 'test' => true],
    Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle::class => ['dev' => true, 'test' => true],
    RiKaZarai\SyliusProductDuplicationPlugin\RiKaZaraiSyliusProductDuplicationPlugin::class => ['all' => true],
];

// tests/Application/config/bootstrap.php
<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__, 2).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__, 2).'/config/bootstrap.php')) {
    require dirname(__DIR__, 2).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__, 2).'/.env');
}

// tests/Application/Kernel.php
<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return \dirname(__DIR__, 2);
    }
}

// tests/Unit/Service/ProductDuplicatorServiceTest.php
<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RiKaZarai\SyliusProductDuplicationPlugin\Service\ProductDuplicatorService;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Core\Factory\ChannelPricingFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

final class ProductDuplicatorServiceTest extends TestCase
{
    private ProductDuplicatorService $productDuplicator;
    private ProductFactoryInterface|MockObject $productFactory;
    private ProductVariantFactoryInterface|MockObject $productVariantFactory;
    private ChannelPricingFactoryInterface|MockObject $channelPricingFactory;
    private ObjectManager|MockObject $productManager;
    private RepositoryInterface|MockObject $productRepository;
    private RepositoryInterface|MockObject $productTranslationRepository;

    protected function setUp(): void
    {
        $this->productFactory = $this->createMock(ProductFactoryInterface::class);
        $this->productVariantFactory = $this->createMock(ProductVariantFactoryInterface::class);
        $this->channelPricingFactory = $this->createMock(ChannelPricingFactoryInterface::class);
        $this->productManager = $this->createMock(ObjectManager::class);
        $this->productRepository = $this->createMock(RepositoryInterface::class);
        $this->productTranslationRepository = $this->createMock(RepositoryInterface::class);

        $this->productDuplicator = new ProductDuplicatorService(
            $this->productFactory,
            $this->productVariantFactory,
            $this->channelPricingFactory,
            $this->productManager,
            $this->productRepository,
            $this->productTranslationRepository
        );
    }

    public function testItCanDuplicateAProduct(): void
    {
        // Given
        $originalProduct = $this->createMock(ProductInterface::class);
        $originalProduct->method('getCode')->willReturn('ORIGINAL_PRODUCT');
        $originalProduct->method('isEnabled')->willReturn(true);
        $originalProduct->method('getTranslations')->willReturn([]);
        $originalProduct->method('getVariants')->willReturn([]);
        $originalProduct->method('getChannels')->willReturn([]);
        $originalProduct->method('getTaxons')->willReturn([]);
        $originalProduct->method('getAttributes')->willReturn([]);
        $originalProduct->method('getOptions')->willReturn([]);
        $originalProduct->method('getAssociations')->willReturn([]);
        $originalProduct->method('getImages')->willReturn([]);
        $originalProduct->method('getTaxCategory')->willReturn(null);
        $originalProduct->method('getShippingCategory')->willReturn(null);

        $duplicatedProduct = $this->createMock(ProductInterface::class);
        
        $this->productFactory
            ->expects($this->once())
            ->method('createNew')
            ->willReturn($duplicatedProduct);

        $this->productRepository
            ->method('findOneBy')
            ->willReturn(null);

        // When
        $result = $this->productDuplicator->duplicateProduct($originalProduct);

        // Then
        $this->assertSame($duplicatedProduct, $result);
    }

    public function testItGeneratesUniqueCodeWhenDuplicating(): void
    {
        // Given
        $originalProduct = $this->createMock(ProductInterface::class);
        $originalProduct->method('getCode')->willReturn('TEST_PRODUCT');
        $originalProduct->method('isEnabled')->willReturn(true);
        $originalProduct->method('getTranslations')->willReturn([]);
        $originalProduct->method('getVariants')->willReturn([]);
        $originalProduct->method('getChannels')->willReturn([]);
        $originalProduct->method('getTaxons')->willReturn([]);
        $originalProduct->method('getAttributes')->willReturn([]);
        $originalProduct->method('getOptions')->willReturn([]);
        $originalProduct->method('getAssociations')->willReturn([]);
        $originalProduct->method('getImages')->willReturn([]);
        $originalProduct->method('getTaxCategory')->willReturn(null);
        $originalProduct->method('getShippingCategory')->willReturn(null);

        $duplicatedProduct = $this->createMock(ProductInterface::class);
        $this->productFactory->method('createNew')->willReturn($duplicatedProduct);

        // Simuler qu'un produit avec le code 'TEST_PRODUCT-copy-1' existe déjà
        $this->productRepository
            ->method('findOneBy')
            ->willReturnMap([
                [['code' => 'TEST_PRODUCT-copy-1'], null, null, $originalProduct],
                [['code' => 'TEST_PRODUCT-copy-2'], null, null, null],
            ]);

        $duplicatedProduct
            ->expects($this->once())
            ->method('setCode')
            ->with('TEST_PRODUCT-copy-2');

        // When
        $this->productDuplicator->duplicateProduct($originalProduct);

        // Then - vérifié par les expectations
    }
}

// tests/Functional/Controller/ProductDuplicationControllerTest.php
<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductDuplicationControllerTest extends WebTestCase
{
    public function testItCanDuplicateAProduct(): void
    {
        // Given
        $client = static::createClient();
        $container = $client->getContainer();
        
        $productFactory = $container->get('sylius.factory.product');
        $productManager = $container->get('sylius.manager.product');
        
        /** @var ProductInterface $product */
        $product = $productFactory->createNew();
        $product->setCode('TEST_PRODUCT_FOR_DUPLICATION');
        $product->setEnabled(true);
        
        // Ajouter une traduction
        $translation = $product->getTranslation('en_US');
        $translation->setName('Test Product');
        $translation->setSlug('test-product');
        
        $productManager->persist($product);
        $productManager->flush();
        
        // When - Effectuer la requête de duplication
        $client->request('POST', sprintf('/admin/products/%d/duplicate', $product->getId()), [
            '_token' => $container->get('security.csrf.token_manager')->getToken('duplicate_product_' . $product->getId())->getValue()
        ]);
        
        // Then
        $this->assertResponseRedirects();
        
        // Vérifier qu'un nouveau produit a été créé
        $productRepository = $container->get('sylius.repository.product');
        $duplicatedProducts = $productRepository->findBy(['code' => 'TEST_PRODUCT_FOR_DUPLICATION-copy-1']);
        
        $this->assertCount(1, $duplicatedProducts);
        $this->assertNotSame($product->getId(), $duplicatedProducts[0]->getId());
    }
    
    public function testItRequiresValidCsrfTokenForDuplication(): void
    {
        // Given
        $client = static::createClient();
        $container = $client->getContainer();
        
        $productFactory = $container->get('sylius.factory.product');
        $productManager = $container->get('sylius.manager.product');
        
        /** @var ProductInterface $product */
        $product = $productFactory->createNew();
        $product->setCode('TEST_PRODUCT_CSRF');
        $product->setEnabled(true);
        
        $translation = $product->getTranslation('en_US');
        $translation->setName('Test Product CSRF');
        $translation->setSlug('test-product-csrf');
        
        $productManager->persist($product);
        $productManager->flush();
        
        // When - Effectuer la requête avec un token invalide
        $client->request('POST', sprintf('/admin/products/%d/duplicate', $product->getId()), [
            '_token' => 'invalid_token'
        ]);
        
        // Then
        $this->assertResponseRedirects();
        
        // Vérifier qu'aucun nouveau produit n'a été créé
        $productRepository = $container->get('sylius.repository.product');
        $duplicatedProducts = $productRepository->findBy(['code' => 'TEST_PRODUCT_CSRF-copy-1']);
        
        $this->assertCount(0, $duplicatedProducts);
    }
}
