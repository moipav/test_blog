<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\Database;
use App\Services\TemplateEngine;

class HomeController
{
    private templateEngine $template;
    private Category $category;
    private Article $article;

    public function __construct(TemplateEngine $template, Database $db)
    {
        $this->template = $template;
        $this->category = new Category($db);
        $this->article = new Article($db);
    }

    public function index(): void
    {
        $categories = $this->category->getAllWithArticles();

        //для каждой категории получаем 3 последние статьи
        $categoriesWithArticles = [];
        foreach ($categories as $category) {
            $category['articles'] = $this->article->getLatestByCategory((int) $category['id'], 3);
            $categoriesWithArticles[] = $category;
        }

        $this->template->render('pages/home.tpl', [
            'categories' => $categoriesWithArticles,
        ]);
    }
}