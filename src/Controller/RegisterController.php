<?php
namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(EntityManagerInterface $entityManager,Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {        
        $user = new User();
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);
                
        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if($existingUser){
                $this->addFlash('error', 'Cette adresse email est déjà utilisée.');
                return $this->redirectToRoute('app_register');
            }
            if(strlen($form->get('plainPassword')->getData()) < 6){
                $this->addFlash('error', 'Ce mot de passe est trop court (minimum 6 caractère).');
                return $this->redirectToRoute('app_register');
            }
            // $user->setEmail($form->get('email')->getData());
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );            
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($hashedPassword);
            $user->setFirstName($form->get('plainFirstName')->getData());
            $user->setLastName($form->get('plainName')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_login');          
        }
        
        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
