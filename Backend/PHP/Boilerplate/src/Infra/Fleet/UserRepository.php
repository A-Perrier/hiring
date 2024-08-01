<?php

namespace Fulll\Infra\Fleet;

use Fulll\Domain\Fleet\Entity\User;
use Fulll\Infra\AbstractRepository;
use PDO;

class UserRepository extends AbstractRepository
{
    /** @var array User[] */
    private array $collection = [];
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fleet_id` int(11) NOT NULL
        )";

        $this->query($sql);
    }

    public function save(User $user): void
    {
        $this->collection[] = $user;
    }

    public function findById(string $id): ?User
    {
        foreach ($this->collection as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }

        return null;
    }
}