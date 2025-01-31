<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function export(): Response
    {
        $products = $this->productRepository->findAll();

        $response = new StreamedResponse(function () use ($products) {
            $handle = fopen('php://output', 'w+');
            // En-tête du CSV
            fputcsv($handle, ['Name', 'Description', 'Price']);
            // Données des produits
            foreach ($products as $product) {
                fputcsv($handle, [$product->getName(), $product->getDescription(), $product->getPrice()]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');

        return $response;
    }
}
