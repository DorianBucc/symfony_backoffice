<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ProductExportService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function export(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        
        $csvData = "name,description,price\n";
        foreach ($products as $product) {
            $csvData .= sprintf("%s,%s,%s\n", 
                $product->getName(), 
                str_replace(",", " ", $product->getDescription()), 
                $product->getPrice()
            );
        }
        
        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');
        
        return $response;
    }
}