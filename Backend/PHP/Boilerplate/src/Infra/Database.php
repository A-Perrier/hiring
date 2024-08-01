<?php

namespace Fulll\Infra;

use Fulll\Infra\{
    FleetRepository,
    FleetVehicleRepository,
    UserRepository,
    VehicleRepository
};
use PDO;
use PDOException;
use Symfony\Component\Dotenv\Dotenv;

class Database
{
    private const array ENTITIES_TO_INITIALIZE = [
        Fleet\UserRepository::class,
        Fleet\FleetRepository::class,
        Fleet\VehicleRepository::class,
        Fleet\FleetVehicleRepository::class
    ];

    private PDO $pdo;
    private string $dbname;
    private string $host;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->registerConfig();

        try {
            $this->pdo = $this->login();
        } catch (PDOException $e) {
            // /!\ Triple equal requires error code to be an integer. May break on some systems ?
            if ($e->getCode() === 1049) { // Unknown database error
                $this->createDatabase();
                $this->initializeTables();
                $this->login();
            } else {
                die('Failed to connect the database : ' . $e->getMessage());
            }
        }
    }

    private function registerConfig(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__, 2) . '/.env');

        $this->dbname = $_ENV['DB_NAME'];
        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function initializeTables(): void
    {
        foreach (self::ENTITIES_TO_INITIALIZE as $entity) {
            $repository = new $entity($this->pdo);
            $repository->createTable();
        }
    }

    private function login(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        return new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    private function createDatabase(): void
    {
        $dsn = "mysql:host=$this->host";
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $this->dbname");
        $pdo = null;
    }
}