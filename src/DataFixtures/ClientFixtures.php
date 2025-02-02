<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $client = new Client();
            $client->setFirstname('Prénom'.$i);
            $client->setLastname('Nom'.$i);
            $client->setEmail('client'.$i.'@mail.com');
            $client->setPhoneNumber('0601020304');
            $client->setAddress('Adresse '.$i);

            $manager->persist($client);
        }

        $manager->flush();
    }
}
