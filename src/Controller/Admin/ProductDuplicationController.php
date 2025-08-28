<?php

declare(strict_types=1);

namespace RiKaZarai\SyliusProductDuplicationPlugin\Controller\Admin;

use RiKaZarai\SyliusProductDuplicationPlugin\Service\ProductDuplicatorService;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/products')]
#[IsGranted('ROLE_ADMIN')]
final class ProductDuplicationController extends AbstractController
{
    public function __construct(
        private ProductDuplicatorService $productDuplicator,
        private RepositoryInterface $productRepository,
        private ObjectManager $productManager
    ) {}

    #[Route('/{id}/duplicate', name: 'rikazarai_sylius_product_duplication_admin_product_duplicate', methods: ['POST'])]
    public function duplicate(int $id, Request $request): Response
    {
        $originalProduct = $this->productRepository->find($id);
        
        if (!$originalProduct instanceof ProductInterface) {
            throw $this->createNotFoundException('Product not found');
        }

        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('duplicate_product_' . $id, $request->request->get('_token'))) {
            $this->addFlash('error', 'rikazarai_sylius_product_duplication.ui.invalid_csrf_token');
            return $this->redirectToRoute('sylius_admin_product_index');
        }

        try {
            $duplicatedProduct = $this->productDuplicator->duplicateProduct($originalProduct);
            $this->productManager->persist($duplicatedProduct);
            $this->productManager->flush();

            $this->addFlash('success', 'rikazarai_sylius_product_duplication.ui.product_duplicated_successfully');
            
            return $this->redirectToRoute('sylius_admin_product_update', ['id' => $duplicatedProduct->getId()]);
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'rikazarai_sylius_product_duplication.ui.product_duplication_failed');
            return $this->redirectToRoute('sylius_admin_product_index');
        }
    }

    #[Route('/bulk-duplicate', name: 'rikazarai_sylius_product_duplication_admin_product_bulk_duplicate', methods: ['POST'])]
    public function bulkDuplicate(Request $request): Response
    {
        $productIds = $request->request->all('ids') ?? [];
        
        if (empty($productIds)) {
            $this->addFlash('error', 'rikazarai_sylius_product_duplication.ui.no_products_selected');
            return $this->redirectToRoute('sylius_admin_product_index');
        }

        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('bulk_duplicate_products', $request->request->get('_token'))) {
            $this->addFlash('error', 'rikazarai_sylius_product_duplication.ui.invalid_csrf_token');
            return $this->redirectToRoute('sylius_admin_product_index');
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($productIds as $id) {
            try {
                $originalProduct = $this->productRepository->find($id);
                if ($originalProduct instanceof ProductInterface) {
                    $duplicated = $this->productDuplicator->duplicateProduct($originalProduct);
                    $this->productManager->persist($duplicated);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                continue;
            }
        }

        if ($successCount > 0) {
            $this->productManager->flush();
            $this->addFlash('success', sprintf('rikazarai_sylius_product_duplication.ui.bulk_duplication_success', $successCount));
        }
        
        if ($errorCount > 0) {
            $this->addFlash('warning', sprintf('rikazarai_sylius_product_duplication.ui.bulk_duplication_partial_error', $errorCount));
        }

        return $this->redirectToRoute('sylius_admin_product_index');
    }
}
