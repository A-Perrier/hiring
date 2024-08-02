<?php

namespace Fulll\App\Fleet\Command;

use Fulll\App\Fleet\CommandHandler\RegisterVehicleCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterVehicleCommand extends Command
{
    protected static string $defaultName = 'register-vehicle';

    public function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet ID')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'Vehicle Plate Number')
            ->setDescription('Add a new vehicle to a fleet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $handler = new RegisterVehicleCommandHandler();
            $result = $handler->handle(
                $input->getArgument('fleetId'),
                $input->getArgument('vehiclePlateNumber')
            );
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