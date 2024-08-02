<?php

namespace Fulll\App\Fleet\CommandHandler;

use Fulll\Infra\Database;
use Fulll\Infra\Fleet\Exception\UserNotFoundException;
use Fulll\Infra\Fleet\Repository\FleetRepository;
use Fulll\Infra\Fleet\Repository\UserRepository;

class CreateFleetCommandHandler
{
    public function handle(int $userId): array
    {
        try {
            $connection = (new Database())->login();

            $userRepository = new UserRepository($connection);
            $user = $userRepository->findById($userId);

            if ($user === null) {
                throw new UserNotFoundException();
            }

            if ($user->getFleetId() !== null) {
                return [
                    'message' => 'A fleet already belongs to this user.',
                    'fleetId' => $user->getFleetId()
                ];
            }

            $fleetRepository = new FleetRepository($connection);
            $fleet = $fleetRepository->create();
            $user->setFleetId($fleet->getId());
            $userRepository->save($user, $fleet);

            return [
                'message' => 'Fleet created.',
                'fleetId' => $fleet->getId()
            ];
        } catch (\Throwable $e) {
            // Watch on Throwable to also catch any Error object that could be thrown
            return [
                'message' => $e->getMessage(),
                'fleetId' => null
            ];
        }
    }
}