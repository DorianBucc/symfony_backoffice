<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ProductExportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/products')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['price' => 'DESC']);  // Trier par prix décroissant
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'product_edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', ['form' => $form->createView(), 'product' => $product]);
    }

    #[Route('/{id}/delete', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
    #[Route('/export', name: 'product_export')]
    public function export(ProductExportService $exportService): Response
    {
        return $exportService->export();
    }
}