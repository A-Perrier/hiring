<?php

namespace Fulll\Infra\Fleet\Repository;

use Fulll\Domain\Fleet\Entity\Fleet;
use Fulll\Domain\Fleet\Entity\User;
use Fulll\Infra\AbstractRepository;
use PDO;

class UserRepository extends AbstractRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fleetId` int(11) NULL DEFAULT NULL,
            FOREIGN KEY (`fleetId`) REFERENCES `fleets` (`id`)
        )";

        $this->query($sql);
    }

    public function save(User $user, Fleet $fleet): User
    {
        $sql = "UPDATE `users` SET `fleetId` = :fleetId WHERE `id` = :id";
        $stmt = $this->query(
            $sql,
            ['fleetId' => $fleet->getId(), 'id' => $user->getId()]
        );

        return $this->findById($user->getId());
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM `users` WHERE `id` = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $row = $stmt->fetch();

        return false === $row ? null : $row;
    }

    public function create(): User
    {
        $sql = "INSERT INTO `users` (`id`) VALUES (NULL)";
        $this->query($sql);
        return $this->findById($this->pdo->lastInsertId());
    }
}