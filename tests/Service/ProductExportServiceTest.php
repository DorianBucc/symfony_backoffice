<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ProductExportService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductExportServiceTest extends TestCase
{
    public function testExport()
    {
        $product1 = (new Product())->setName('Produit 1')->setDescription('Desc 1, test')->setPrice(10.50);
        $product2 = (new Product())->setName('Produit 2')->setDescription('Desc 2')->setPrice(20.00);

        $mockRepository = $this->createMock(EntityRepository::class);
        $mockRepository->method('findAll')->willReturn([$product1, $product2]);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager->method('getRepository')->willReturn($mockRepository);

        $service = new ProductExportService($mockEntityManager);
        $response = $service->export();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="products.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsv = "name,description,price\nProduit 1,Desc 1 test,10.5\nProduit 2,Desc 2,20\n";
        $this->assertEquals($expectedCsv, $response->getContent());
    }
}
