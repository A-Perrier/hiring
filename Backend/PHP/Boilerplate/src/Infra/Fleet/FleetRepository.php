<?php

namespace Fulll\Infra\Fleet;

use Fulll\Infra\AbstractRepository;
use PDO;

class FleetRepository extends AbstractRepository
{
    /** @var array Fleet[] */
    private array $collection = [];
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `fleets` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        )";

        $this->query($sql);
    }
}