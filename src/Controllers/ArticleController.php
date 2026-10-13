<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Services\Database;
use App\Services\TemplateEngine;

class ArticleController
{
    private templateEngine $template;
    private Article $article;

    public function __construct(TemplateEngine $template, Database $db)
    {
        $this->template = $template;
        $this->article = new Article($db);
    }

    public function index(int $id): void
    {
        $article = $this->article->getArticle($id);

        if (!$article) {
            http_response_code(404);
            $this->template->render('pages/404.tpl');
            return;
        }
        //увеличиваем счетчик просмотров
        $this->article->incrementViews((int)$article['id']);
        //получаем похожие статьи
        $categories = $this->article->getCategories((int)$article['id']);
        $relatedArticles = $this->article->getRelated((int)$article['id']);
        $this->template->render('pages/article.tpl', [
            'article' => $article,
            'categories' => $categories,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}