<?php

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:add-client', description: 'Ajoute un client')]
class AddClientCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $firstname = $helper->ask($input, $output, new Question('Prénom du client : '));
        $lastname = $helper->ask($input, $output, new Question('Nom du client : '));
        $email = $helper->ask($input, $output, new Question('Email du client : '));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln('<error>Email invalide.</error>');
            return Command::FAILURE;
        }

        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $output->writeln('<info>Client ajouté avec succès !</info>');
        return Command::SUCCESS;
    }
}
