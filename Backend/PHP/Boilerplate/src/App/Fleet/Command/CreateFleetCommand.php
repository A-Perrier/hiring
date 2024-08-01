<?php

namespace Fulll\App\Fleet\Command;

use Fulll\Domain\Fleet\Entity\User;
use Fulll\Infra\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFleetCommand extends Command
{
    protected static $defaultName = 'create';

     public function configure()
     {
         $this
             ->setName('create')
             ->addArgument('userId', InputArgument::REQUIRED, 'User ID')
             ->setDescription('Create a new fleet related to the current user')
         ;
     }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * Try to fetch user first. If not found, create it.
         */
        $user = new User($input->getArgument('userId'));

        /**
         * Test purposes.
         * To move quickly at the right place :)
         */
        $database = new Database();
        $database->initializeTables();

        $output->writeln("Creating fleet for user " . $input->getArgument('userId'));
        $output->writeln(static::$defaultName);
        return Command::SUCCESS;
    }
}