<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE']
        );

        try {
            $this->connection = new PDO(
                $dsn,
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Выполнить запрос и получить все результаты
     */
    public function fetchAll(string $query, array $params = []): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Выполнить запрос и получить одну запись
     */
    public function fetch(string $query, array $params = []): array|false
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Выполнить запрос (INSERT, UPDATE, DELETE)
     */
    public function execute(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Получить последний вставленный ID
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}