<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\H;
use App\Models\Article;
use App\Models\Category;
use App\Services\Database;
use App\Services\TemplateEngine;

class CategoryController
{
    private TemplateEngine $template;
    private Category $category;
    private Article $article;

    private int $perPage = 9;

    public function __construct(TemplateEngine $template, Database $db)
    {
        $this->template = $template;
        $this->category = new Category($db);
        $this->article = new Article($db);
    }

    public function index(): void
    {
        $categories = $this->category->findAll();

        $this->template->render('pages/categories.tpl', ['categories' => $categories]);
    }

    public function show(int $id): void
    {
        $result= $this->category->findById($id);

        if(!$result){
            http_response_code(404);
            $this->template->render('pages/404.tpl');
            return;
        }

        // 2. Получаем параметры сортировки и пагинации из URL
        $sortBy = $_GET['sort'] ?? 'published_at';
        $sortOrder = $_GET['order'] ?? 'DESC';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        // 3. Получаем статьи с сортировкой и пагинацией
        $articles = $this->article->getByCategory(
            (int)$result['id'],
            $sortBy,
            $sortOrder,
            $this->perPage,
            $offset
        );

        // 4. Получаем общее количество статей для пагинации
        $totalArticles = $this->article->countByCategory((int)$result['id']);
        $totalPages = (int)ceil($totalArticles / $this->perPage);


        // 5. Рендерим шаблон
        $this->template->render('pages/category.tpl', [
            'category' => $result,
            'articles' => $articles,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
        ]);
    }
}