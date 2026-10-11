<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Database;

class Category
{
    private readonly Database $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM categories";

        return $this->db->fetchAll($query);
    }

    public function findById(int $id): array|false
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        return $this->db->fetch($query, ['id' => $id]);
    }
}