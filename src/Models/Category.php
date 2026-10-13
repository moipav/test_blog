<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Database;

class Category
{
    private readonly Database $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
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

    /**
     * Найти категорию по slug
     */
    public function findBySlug(string $slug): array|false
    {
        $query = "SELECT * FROM categories WHERE slug = :slug LIMIT 1";
        return $this->db->fetch($query, ['slug' => $slug]);
    }

    /**
     * Получить все категории, в которых есть статьи
     */
    public function getAllWithArticles(): array
    {
        $query = "
            SELECT DISTINCT c.*
            FROM categories c
            INNER JOIN category_article ca ON c.id = ca.category_id
            ORDER BY c.name ASC
        ";

        return $this->db->fetchAll($query);
    }

}