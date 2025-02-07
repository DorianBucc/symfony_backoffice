<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setName($faker->word()) 
                    ->setDescription('Description')
                    ->setPrice($faker->randomFloat(2, 1, 50));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
