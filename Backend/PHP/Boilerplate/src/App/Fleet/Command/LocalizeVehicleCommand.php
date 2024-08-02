<?php

namespace Fulll\App\Fleet\Command;

use Fulll\App\Fleet\CommandHandler\LocalizeVehicleCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LocalizeVehicleCommand extends Command
{
    protected static string $defaultName = 'localize-vehicle';

    public function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet ID')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'Vehicle Plate Number')
            ->addOption('lat', null, InputOption::VALUE_OPTIONAL, 'Latitude')
            ->addOption('lng', null, InputOption::VALUE_OPTIONAL, 'Longitude')
            ->setDescription('Localize a vehicle if it belongs to a fleet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (($input->getOption('lat') === null) || ($input->getOption('lng') === null)) {
            $output->writeln('<error>Please specify the latitude and/or longitude option (--lat and --lng)</error>');
            return Command::FAILURE;
        }

        try {
            $handler = new LocalizeVehicleCommandHandler();
            $result = $handler->handle(
                $input->getArgument('fleetId'),
                $input->getArgument('vehiclePlateNumber'),
                $input->getOption('lat'),
                $input->getOption('lng')
            );
            $output->writeln($result['message']);
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }

        return Command::SUCCESS;
    }
}