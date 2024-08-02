<?php

namespace Fulll\App\Fleet\Command;

use Fulll\Infra\Database;
use Fulll\Infra\Fleet\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static string $defaultName = 'create-users';

    public function configure()
    {
        $this
            ->setName(self::$defaultName)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Running fixtures ...');
        $userRepository = new UserRepository((new Database())->login());
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $user = $userRepository->create();
            $ids[] = $user->getId();
        }

        $output->writeln(sprintf('Created users with following IDs : %s. You can use these to run your tests.', implode(' - ', $ids)));
        return Command::SUCCESS;
    }
}