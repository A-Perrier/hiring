<?php

namespace Fulll\App\Fleet\Command;

use Fulll\App\Fleet\CommandHandler\CreateFleetCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFleetCommand extends Command
{
    protected static string $defaultName = 'create';

     public function configure()
     {
         $this
             ->setName(self::$defaultName)
             ->addArgument('userId', InputArgument::REQUIRED, 'User ID')
             ->setDescription('Create a new fleet related to the current user')
         ;
     }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $handler = new CreateFleetCommandHandler();
            $result = $handler->handle($input->getArgument('userId'));
            $output->writeln($result['message']);
            if ($result['fleetId'] !== null) {
                $output->writeln($result['fleetId']);
            }
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }

        return Command::SUCCESS;
    }
}