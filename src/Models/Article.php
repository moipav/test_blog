<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Database;

class Article
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getArticle(int $id): array|false
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        return $this->db->fetch($sql, ['id' => $id]);

    }

    /**
     * Получить последние N статей для категории
     */
    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $query = "
            SELECT a.*
            FROM articles a
            INNER JOIN category_article ca ON a.id = ca.article_id
            WHERE ca.category_id = :category_id
            ORDER BY a.published_at DESC
            LIMIT :limit
        ";

        return $this->db->fetchAll($query, [
            'category_id' => $categoryId,
            'limit' => $limit,
        ]);
    }

    /**
     * Найти статью по slug
     */
    public function findBySlug(string $slug): array|false
    {
        $query = "SELECT * FROM articles WHERE slug = :slug LIMIT 1";
        return $this->db->fetch($query, ['slug' => $slug]);
    }

    /**
     * Получить статьи категории с сортировкой и пагинацией
     */
    public function getByCategory(
        int    $categoryId,
        string $sortBy = 'published_at',
        string $sortOrder = 'DESC',
        int    $limit = 10,
        int    $offset = 0
    ): array
    {
        // Валидация параметров сортировки
        $allowedSorts = ['published_at', 'views'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'published_at';
        }

        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $query = "
            SELECT a.*
            FROM articles a
            INNER JOIN category_article ca ON a.id = ca.article_id
            WHERE ca.category_id = :category_id
              AND a.published_at IS NOT NULL
            ORDER BY a.{$sortBy} {$sortOrder}
            LIMIT :limit OFFSET :offset
        ";

        return $this->db->fetchAll($query, [
            'category_id' => $categoryId,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Получить количество статей в категории
     */
    public function countByCategory(int $categoryId): int
    {
        $query = "
            SELECT COUNT(*) as total
            FROM articles a
            INNER JOIN category_article ca ON a.id = ca.article_id
            WHERE ca.category_id = :category_id
              AND a.published_at IS NOT NULL
        ";

        $result = $this->db->fetch($query, ['category_id' => $categoryId]);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Увеличить количество просмотров
     */
    public function incrementViews(int $articleId): void
    {
        $query = "UPDATE articles SET views = views + 1 WHERE id = :id";
        $this->db->execute($query, ['id' => $articleId]);
    }

    /**
     * Получить категории статьи
     */
    public function getCategories(int $articleId): array
    {
        $query = "
            SELECT c.*
            FROM categories c
            INNER JOIN category_article ca ON c.id = ca.category_id
            WHERE ca.article_id = :article_id
            ORDER BY c.name ASC
        ";

        return $this->db->fetchAll($query, ['article_id' => $articleId]);
    }

    /**
     * Получить похожие статьи (из тех же категорий)
     */
    public function getRelated(int $articleId, int $limit = 3): array
    {
        $query = "
            SELECT DISTINCT a.*
            FROM articles a
            INNER JOIN category_article ca ON a.id = ca.article_id
            WHERE ca.category_id IN (
                SELECT category_id 
                FROM category_article 
                WHERE article_id = :article_id_1
            )
            AND a.id != :article_id_2
            AND a.published_at IS NOT NULL
            ORDER BY a.published_at DESC
            LIMIT :limit
        ";

        return $this->db->fetchAll($query, [
            'article_id_1' => $articleId,
            'article_id_2' => $articleId,
            'limit' => $limit,
        ]);
    }

}