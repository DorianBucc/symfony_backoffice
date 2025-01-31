<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['admin@example.com', 'Admin', 'User', 'ROLE_ADMIN'],
            ['manager@example.com', 'Manager', 'User', 'ROLE_MANAGER'],
            ['user@example.com', 'Standard', 'User', 'ROLE_USER']
        ];

        foreach ($users as [$email, $firstname, $lastname, $role]) {
            $user = new User();
            $user->setEmail($email)
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setRoles([$role])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}