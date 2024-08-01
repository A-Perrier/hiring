<?php

namespace Fulll\App\Fleet\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LocalizeVehicleCommand extends Command
{
    protected static $defaultName = 'localize-vehicle';

    public function configure(): void
    {
        $this
            ->setName('localize-vehicle')
            ->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet ID')
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED, 'Vehicle Plate Number')
            ->addOption('lat', null, InputOption::VALUE_REQUIRED, 'Latitude')
            ->addOption('lng', null, InputOption::VALUE_REQUIRED, 'Longitude')
            ->setDescription('')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Inside of LocalizeVehicle command');
        $output->writeln('Location : ' . (string) $input->getOption('lat') . '-' . (string) $input->getOption('lng'));
        $output->writeln(static::$defaultName);
        return Command::SUCCESS;
    }
}