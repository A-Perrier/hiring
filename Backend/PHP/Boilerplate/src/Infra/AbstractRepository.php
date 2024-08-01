<?php

namespace Fulll\Infra;

use PDO;
use PDOStatement;

class AbstractRepository
{

    protected PDO $pdo;

    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}