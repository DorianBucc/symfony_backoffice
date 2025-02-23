<?php
namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:import-products', description: 'Import products from CSV')]
class ProductImportCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = __DIR__ . '/../../public/products.csv';

        if (!file_exists($filePath)) {
            $io->error("Le fichier products.csv n'existe pas dans public/");
            return Command::FAILURE;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            fgetcsv($handle); // Ignorer l'en-tête
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $product = new Product();
                $product->setName($data[0]);
                $product->setDescription($data[1]);
                $product->setPrice((float) $data[2]);
                
                $this->entityManager->persist($product);
            }
            fclose($handle);
            $this->entityManager->flush();
            $io->success('Produits importés avec succès !');
        }
        
        return Command::SUCCESS;
    }
}