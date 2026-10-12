<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\TemplateEngine;

class ArticleController
{
    private templateEngine $template;
    private Category $category;
    private Article $article;

    private int $perPage = 9;
    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
        $this->category = new Category();
        $this->article = new Article();
    }

/*
 * ● Страница статьи:

    ○ Вывести всю информацию о статье
        - ○ Изображение
        - ○ Название
        - ○ Описание
        - ○ Текст
        - ○ Категория (одна или несколько)
        - ○ Кол-во просмотров
        - ○ Вывести блок из 3 похожих статей
*/
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